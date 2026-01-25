<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'phone.verified', 'approved']);
    }

    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 15);
        $uploads = auth()->user()->uploads()->latest()->paginate($perPage);

        return view('users.uploads.index', compact('uploads'));
    }
}
