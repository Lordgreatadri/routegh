<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\Upload;
use App\Models\SystemMetric;

class ContactsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $q = request('q');
        $perPage = max(10, $request->query('per_page', 10));
        
        $contacts = Contact::with(['user', 'contactGroup'])
            ->when($q, function ($qb, $val) {
                $qb->where('name', 'like', "%{$val}%")
                   ->orWhere('phone', 'like', "%{$val}%");
            })
            ->latest()
            ->paginate($perPage)
            ->appends(request()->query());

        $stats = [
            'total_contacts' => Contact::count(),
            'total_groups' => ContactGroup::count(),
            'contacts_in_groups' => Contact::whereNotNull('contact_group_id')->count(),
            'recent_imports' => Contact::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        return view('admin.contacts.index', compact('contacts', 'stats'));
    }

    public function uploadForm()
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $groups = ContactGroup::orderBy('name')->get();
        return view('admin.contacts.upload', compact('groups'));
    }

    public function upload(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls',
            'contact_group_id' => 'required|exists:contact_groups,id',
        ]);

        $file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        
        // Create upload record
        $upload = Upload::create([
            'user_id' => auth()->id(),
            'filename' => $file->hashName(),
            'original_name' => $file->getClientOriginalName(),
            'file_type' => $extension,
            'status' => 'processing',
        ]);

        $imported = 0;
        $skipped = 0;
        $errors = [];

        try {
            // Handle Excel files
            if (in_array($extension, ['xlsx', 'xls'])) {
                // Use Laravel Excel to convert to array without the import class
                $data = \Maatwebsite\Excel\Facades\Excel::toArray([], $file);
                
                if (empty($data) || empty($data[0])) {
                    $upload->update([
                        'status' => 'failed',
                        'error_log' => ['error' => 'The file is empty or invalid'],
                    ]);
                    return redirect()->route('admin.contacts.index')->with('error', 'The file is empty or invalid');
                }

                $rows = $data[0];
                $header = array_shift($rows); // First row as header
                $upload->update(['total_rows' => count($rows)]);

                foreach ($rows as $index => $row) {
                    $rowData = array_combine($header, $row);
                    $name = $rowData['name'] ?? ($rowData['full_name'] ?? null);
                    $phone = $rowData['phone'] ?? null;
                    
                    if (!$phone) {
                        $skipped++;
                        $errors[] = "Row " . ($index + 2) . ": Missing phone number";
                        continue;
                    }
                    
                    $phoneNormalized = preg_replace('/\D/', '', $phone);
                    
                    if (!Contact::isValidPhoneNumber($phoneNormalized)) {
                        $skipped++;
                        $errors[] = "Row " . ($index + 2) . ": Invalid phone number";
                        continue;
                    }
                    
                    if (Contact::where('phone', $phoneNormalized)->exists()) {
                        $skipped++;
                        $errors[] = "Row " . ($index + 2) . ": Duplicate phone number";
                        continue;
                    }

                    Contact::create([
                        'name' => $name ?? 'Imported contact',
                        'phone' => $phoneNormalized,
                        'user_id' => auth()->id(),
                        'upload_id' => $upload->id,
                        'contact_group_id' => $validated['contact_group_id'] ?? null,
                        'meta' => [
                            'imported_at' => now()->toDateTimeString(),
                            'original_name' => $name,
                            'source_file' => $file->getClientOriginalName(),
                        ],
                    ]);
                    $imported++;
                }
            } else {
                // Handle CSV files
                $handle = fopen($file->getRealPath(), 'r');
                $header = null;
                $rowIndex = 0;

                while (($row = fgetcsv($handle, 0, ',')) !== false) {
                    if (!$header) {
                        $header = $row;
                        continue;
                    }
                    
                    $rowIndex++;
                    $data = array_combine($header, $row);
                    $name = $data['name'] ?? ($data['full_name'] ?? null);
                    $phone = $data['phone'] ?? null;
                    
                    if (!$phone) {
                        $skipped++;
                        $errors[] = "Row {$rowIndex}: Missing phone number";
                        continue;
                    }
                    
                    $phoneNormalized = preg_replace('/\D/', '', $phone);
                    
                    if (!Contact::isValidPhoneNumber($phoneNormalized)) {
                        $skipped++;
                        $errors[] = "Row {$rowIndex}: Invalid phone number";
                        continue;
                    }
                    
                    if (Contact::where('phone', $phoneNormalized)->exists()) {
                        $skipped++;
                        $errors[] = "Row {$rowIndex}: Duplicate phone number";
                        continue;
                    }

                    Contact::create([
                        'name' => $name ?? 'Imported contact',
                        'phone' => $phoneNormalized,
                        'user_id' => auth()->id(),
                        'upload_id' => $upload->id,
                        'contact_group_id' => $validated['contact_group_id'] ?? null,
                        'meta' => [
                            'imported_at' => now()->toDateTimeString(),
                            'original_name' => $name,
                            'source_file' => $file->getClientOriginalName(),
                        ],
                    ]);
                    $imported++;
                }

                if (!isset($rowIndex)) {
                    $rowIndex = 0;
                }
                $upload->update(['total_rows' => $rowIndex]);
                fclose($handle);
            }

            // Update upload record
            $upload->update([
                'status' => 'completed',
                'processed_rows' => $imported,
                'failed_rows' => $skipped,
                'error_log' => $errors,
            ]);

            // Update system metrics
            SystemMetric::recordMetrics();

            // Prepare success/warning message
            $message = "Imported {$imported} contacts";
            
            if ($skipped > 0) {
                $message .= ", skipped {$skipped} rows";
                
                // Show first few errors as examples
                if (!empty($errors)) {
                    $errorSample = array_slice($errors, 0, 5);
                    $errorMessage = "Skipped rows: " . implode(' | ', $errorSample);
                    if (count($errors) > 5) {
                        $errorMessage .= " | +" . (count($errors) - 5) . " more errors";
                    }
                    $errorMessage .= ". Upload ID: {$upload->id}";
                    
                    return redirect()->route('admin.contacts.index')
                        ->with('warning', $message . '. ' . $errorMessage);
                }
            }
            
            $message .= ". Upload ID: {$upload->id}";
            return redirect()->route('admin.contacts.index')->with('success', $message);

        } catch (\Exception $e) {
            $upload->update([
                'status' => 'failed',
                'error_log' => ['error' => $e->getMessage()],
            ]);
            
            return redirect()->route('admin.contacts.index')
                ->with('error', 'Error importing file: ' . $e->getMessage());
        }
    }

    public function create()
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $groups = ContactGroup::orderBy('name')->get();
        return view('admin.contacts.create', compact('groups'));
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'contact_group_id' => 'nullable|exists:contact_groups,id',
        ]);

        // Normalize phone number
        $phoneNormalized = preg_replace('/\D/', '', $validated['phone']);

        // Check if phone already exists
        if (Contact::where('phone', $phoneNormalized)->exists()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'A contact with this phone number already exists.');
        }

        Contact::create([
            'name' => $validated['name'],
            'phone' => $phoneNormalized,
            'contact_group_id' => $validated['contact_group_id'] ?? null,
            'user_id' => auth()->id(),
            'meta' => [
                'created_manually' => true,
                'created_at' => now()->toDateTimeString(),
            ],
        ]);

        return redirect()->route('admin.contacts.index')->with('success', 'Contact added successfully');
    }

    public function edit(Contact $contact)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $groups = ContactGroup::orderBy('name')->get();
        return view('admin.contacts.edit', compact('contact', 'groups'));
    }

    public function update(Request $request, Contact $contact)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'contact_group_id' => 'nullable|exists:contact_groups,id',
        ]);

        // Normalize phone number
        $phoneNormalized = preg_replace('/\D/', '', $validated['phone']);

        // Check if phone already exists (excluding current contact)
        if (Contact::where('phone', $phoneNormalized)->where('id', '!=', $contact->id)->exists()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'A contact with this phone number already exists.');
        }

        $contact->update([
            'name' => $validated['name'],
            'phone' => $phoneNormalized,
            'contact_group_id' => $validated['contact_group_id'] ?? null,
        ]);

        return redirect()->route('admin.contacts.index')->with('success', 'Contact updated successfully');
    }

    public function destroy(Contact $contact)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $contact->delete();
        return redirect()->route('admin.contacts.index')->with('success', 'Contact deleted');
    }
}
