<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Contact;
use App\Models\ContactGroup;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display a listing of contacts.
     */
    public function index(Request $request): View
    {
        abort_if(!auth()->user()->isApproved(), 403);

        $query = auth()->user()->contacts();

        // Filter by group
        if ($request->has('group_id')) {
            $query->where('contact_group_id', $request->group_id);
        }

        $contacts = $query->latest()->paginate(20);
        $groups = auth()->user()->contactGroups()->get();

        return view('contacts.index', [
            'contacts' => $contacts,
            'groups' => $groups,
        ]);
    }

    /**
     * Show the form for creating a new contact.
     */
    public function create(): View
    {
        abort_if(!auth()->user()->isApproved(), 403);

        $groups = auth()->user()->contactGroups()->pluck('name', 'id');

        return view('contacts.create', ['groups' => $groups]);
    }

    /**
     * Store a newly created contact in storage.
     */
    public function store(StoreContactRequest $request): RedirectResponse
    {
        auth()->user()->contacts()->create($request->validated());

        return redirect()->route('contacts.index')
            ->with('success', 'Contact created successfully.');
    }

    /**
     * Display the specified contact.
     */
    public function show(Contact $contact): View
    {
        abort_if(auth()->user()->id !== $contact->user_id, 403);

        return view('contacts.show', ['contact' => $contact]);
    }

    /**
     * Show the form for editing the specified contact.
     */
    public function edit(Contact $contact): View
    {
        abort_if(auth()->user()->id !== $contact->user_id, 403);

        $groups = auth()->user()->contactGroups()->pluck('name', 'id');

        return view('contacts.edit', [
            'contact' => $contact,
            'groups' => $groups,
        ]);
    }

    /**
     * Update the specified contact in storage.
     */
    public function update(StoreContactRequest $request, Contact $contact): RedirectResponse
    {
        abort_if(auth()->user()->id !== $contact->user_id, 403);

        $contact->update($request->validated());

        return redirect()->route('contacts.show', $contact)
            ->with('success', 'Contact updated successfully.');
    }

    /**
     * Remove the specified contact from storage.
     */
    public function destroy(Contact $contact): RedirectResponse
    {
        abort_if(auth()->user()->id !== $contact->user_id, 403);

        $contact->delete();

        return redirect()->route('contacts.index')
            ->with('success', 'Contact deleted successfully.');
    }
}
