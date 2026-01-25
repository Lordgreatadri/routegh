<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Upload;

class UploadsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        
        $status = $request->query('status');
        $perPage = max(10, $request->query('per_page', 10));
        
        $uploads = Upload::with('user')
            ->when($status, function ($qb, $val) {
                $qb->where('status', $val);
            })
            ->latest()
            ->paginate($perPage)
            ->appends(request()->query());

        $stats = [
            'total_uploads' => Upload::count(),
            'pending' => Upload::where('status', 'pending')->count(),
            'processing' => Upload::where('status', 'processing')->count(),
            'completed' => Upload::where('status', 'completed')->count(),
            'failed' => Upload::where('status', 'failed')->count(),
        ];

        return view('admin.uploads.index', compact('uploads', 'stats'));
    }

    public function show(Upload $upload)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        
        $upload->load('user', 'contacts');
        
        return view('admin.uploads.show', compact('upload'));
    }

    public function destroy(Upload $upload)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        
        $upload->delete();
        
        return redirect()->route('admin.uploads.index')->with('success', 'Upload deleted successfully');
    }
}
