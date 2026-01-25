<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ContactGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ContactGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'phone.verified', 'approved']);
    }

    public function index()
    {
        $groups = auth()->user()->contactGroups()->latest()->paginate(20);
        return view('users.contact-groups.index', compact('groups'));
    }

    public function create()
    {
        return view('users.contact-groups.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => ['required', 'string', 'max:120']]);

        auth()->user()->contactGroups()->create($request->only('name', 'description'));

        return Redirect::route('contact-groups.index')->with('success', 'Group created.');
    }

    public function edit(ContactGroup $contactGroup)
    {
        abort_if(auth()->id() !== $contactGroup->user_id, 403);
        return view('users.contact-groups.edit', ['group' => $contactGroup]);
    }

    public function update(Request $request, ContactGroup $contactGroup)
    {
        abort_if(auth()->id() !== $contactGroup->user_id, 403);

        $request->validate(['name' => ['required', 'string', 'max:120']]);

        $contactGroup->update($request->only('name', 'description'));

        return Redirect::route('contact-groups.index')->with('success', 'Group updated.');
    }

    public function destroy(ContactGroup $contactGroup)
    {
        abort_if(auth()->id() !== $contactGroup->user_id, 403);

        $contactGroup->delete();

        return Redirect::route('contact-groups.index')->with('success', 'Group deleted.');
    }
}
