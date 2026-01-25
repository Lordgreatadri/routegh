<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactGroup;
use App\Models\Contact;

class ContactGroupsController extends Controller
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
        
        $groups = ContactGroup::withCount('contacts')
            ->with('user')
            ->when($q, function ($qb, $val) {
                $qb->where('name', 'like', "%{$val}%")
                   ->orWhere('description', 'like', "%{$val}%");
            })
            ->latest()
            ->paginate($perPage)
            ->appends(request()->query());

        $stats = [
            'total_groups' => ContactGroup::count(),
            'total_contacts_in_groups' => Contact::whereNotNull('contact_group_id')->count(),
            'groups_with_contacts' => ContactGroup::has('contacts')->count(),
            'empty_groups' => ContactGroup::doesntHave('contacts')->count(),
        ];

        return view('admin.contact-groups.index', compact('groups', 'stats'));
    }

    public function create()
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        return view('admin.contact-groups.create');
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:contact_groups,name',
            'description' => 'nullable|string|max:1000',
        ]);

        $group = ContactGroup::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('admin.contact-groups.index')
            ->with('success', "Contact group '{$group->name}' created successfully.");
    }

    public function show(ContactGroup $contactGroup)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        
        $contactGroup->loadCount('contacts');
        $contacts = $contactGroup->contacts()
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('admin.contact-groups.show', compact('contactGroup', 'contacts'));
    }

    public function edit(ContactGroup $contactGroup)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $contactGroup->loadCount('contacts');
        return view('admin.contact-groups.edit', compact('contactGroup'));
    }

    public function update(Request $request, ContactGroup $contactGroup)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:contact_groups,name,' . $contactGroup->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $contactGroup->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('admin.contact-groups.index')
            ->with('success', "Contact group '{$contactGroup->name}' updated successfully.");
    }

    public function destroy(ContactGroup $contactGroup)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $contactsCount = $contactGroup->contacts()->count();
        $groupName = $contactGroup->name;

        // Set all contacts in this group to have no group
        if ($contactsCount > 0) {
            $contactGroup->contacts()->update(['contact_group_id' => null]);
        }

        $contactGroup->delete();

        $message = "Contact group '{$groupName}' deleted successfully.";
        if ($contactsCount > 0) {
            $message .= " {$contactsCount} contact(s) were moved to 'No Group'.";
        }

        return redirect()
            ->route('admin.contact-groups.index')
            ->with('success', $message);
    }
}
