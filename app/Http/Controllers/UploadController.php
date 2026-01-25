<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadFileRequest;
use App\Models\Upload;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Facades\Excel;

class UploadController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display a listing of uploads.
     */
    public function index(): View
    {
        abort_if(!auth()->user()->isApproved(), 403);

        $uploads = auth()->user()->uploads()
            ->latest()
            ->paginate(10);

        return view('uploads.index', ['uploads' => $uploads]);
    }

    /**
     * Show the form for creating a new upload.
     */
    public function create(): View
    {
        abort_if(!auth()->user()->isApproved(), 403);

        return view('uploads.create');
    }

    /**
     * Store a newly created upload in storage.
     */
    public function store(UploadFileRequest $request): RedirectResponse
    {
        $file = $request->file('file');
        
        // Determine file type
        $fileType = match($file->getClientOriginalExtension()) {
            'csv' => 'csv',
            'xlsx' => 'xlsx',
            'xls' => 'xls',
        };

        // Store file
        $path = $file->store('uploads', 'public');
        
        // Create upload record
        $upload = auth()->user()->uploads()->create([
            'filename' => basename($path),
            'original_name' => $file->getClientOriginalName(),
            'file_type' => $fileType,
            'status' => 'pending',
        ]);

        // Dispatch job to process file
        // dispatch(new ProcessFileUploadJob($upload))->delay(now()->addSeconds(2));

        return redirect()->route('uploads.show', $upload)
            ->with('success', 'File uploaded successfully. Processing will start shortly.');
    }

    /**
     * Display the specified upload.
     */
    public function show(Upload $upload): View
    {
        abort_if(auth()->user()->id !== $upload->user_id, 403);

        $contacts = $upload->contacts()
            ->latest()
            ->paginate(15);

        return view('uploads.show', [
            'upload' => $upload,
            'contacts' => $contacts,
        ]);
    }

    /**
     * Remove the specified upload from storage.
     */
    public function destroy(Upload $upload): RedirectResponse
    {
        abort_if(auth()->user()->id !== $upload->user_id, 403);

        $upload->delete();

        return redirect()->route('uploads.index')
            ->with('success', 'Upload deleted successfully.');
    }
}
