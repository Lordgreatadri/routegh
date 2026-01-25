<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Imports\ContactsImport;
use App\Models\Contact;
use App\Models\ContactGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'phone.verified', 'approved']);
    }

    public function index(Request $request)
    {
        $query = auth()->user()->contacts();

        if ($request->has('group_id')) {
            $query->where('contact_group_id', $request->group_id);
        }

        $perPage = $request->query('per_page', 15);
        $contacts = $query->latest()->paginate($perPage)->withQueryString();
        $groups = auth()->user()->contactGroups()->get();

        return view('users.contacts.index', compact('contacts', 'groups'));
    }

    public function create()
    {
        $groups = auth()->user()->contactGroups()->pluck('name', 'id');
        return view('users.contacts.create', compact('groups'));
    }

    public function store(StoreContactRequest $request)
    {
        auth()->user()->contacts()->create($request->validated());

        return Redirect::route('contacts.index')->with('success', 'Contact created successfully.');
    }

    public function edit(Contact $contact)
    {
        abort_if(auth()->id() !== $contact->user_id, 403);

        $groups = auth()->user()->contactGroups()->pluck('name', 'id');
        return view('users.contacts.edit', compact('contact', 'groups'));
    }

    public function update(StoreContactRequest $request, Contact $contact)
    {
        abort_if(auth()->id() !== $contact->user_id, 403);

        $contact->update($request->validated());

        return Redirect::route('contacts.index')->with('success', 'Contact updated.');
    }

    public function destroy(Contact $contact)
    {
        abort_if(auth()->id() !== $contact->user_id, 403);

        $contact->delete();

        return Redirect::route('contacts.index')->with('success', 'Contact deleted.');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file'],
            'group_id' => [
                'required',
                Rule::exists('contact_groups', 'id')->where(function ($query) {
                    $query->where('user_id', auth()->id());
                }),
            ],
        ]);

        $file = $request->file('file');
        $path = $file->store('uploads');

        $defaultGroupId = $request->input('group_id') ?: null;

        // Create an Upload record for auditing/progress
        $upload = \App\Models\Upload::create([
            'user_id' => auth()->id(),
            'filename' => $path,
            'original_name' => $file->getClientOriginalName(),
            'file_type' => strtolower($file->getClientOriginalExtension()),
            'status' => 'pending',
        ]);

        // Determine total rows in file (sum across sheets)
        try {
            $array = Excel::toArray(null, storage_path('app/' . $path));
            $totalRows = 0;
            foreach ($array as $sheet) {
                $totalRows += is_array($sheet) ? count($sheet) : 0;
            }
        } catch (\Throwable $e) {
            // couldn't read file to count rows; default to 0
            $totalRows = 0;
        }

        // store total rows
        $upload->update(['total_rows' => $totalRows]);

        try {
            $upload->markAsProcessing();

            // Import using maatwebsite/excel (pass default group id and upload)
            Excel::import(new ContactsImport(auth()->user(), $defaultGroupId, $upload), storage_path('app/' . $path));

            // compute failed rows (total - processed)
            $failed = max(0, ($upload->total_rows ?? 0) - ($upload->processed_rows ?? 0));
            $upload->update(['failed_rows' => $failed]);

            $upload->markAsCompleted();
            
            // Update system metrics for today
            $successfulRecipients = ($upload->processed_rows ?? 0) - $failed;
            if ($successfulRecipients > 0) {
                \App\Models\SystemMetric::incrementUploads($successfulRecipients);
            }
        } catch (\Throwable $e) {
            $upload->markAsFailed([$e->getMessage()]);
            // ensure failed_rows is set
            $failed = max(0, ($upload->total_rows ?? 0) - ($upload->processed_rows ?? 0));
            $upload->update(['failed_rows' => $failed]);

            return Redirect::route('contacts.index')->with('error', 'Upload failed: ' . $e->getMessage());
        }

        return Redirect::route('contacts.index')->with('success', 'Contacts uploaded.');
    }
}
