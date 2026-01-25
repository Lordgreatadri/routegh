<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LogViewerController extends Controller
{
    public function index(Request $request)
    {
        $logType = $request->query('type', 'laravel');
        $limit = (int) $request->query('limit', 100);
        $filter = $request->query('filter', 'error'); // error, warning, info, all
        
        $logs = [];
        $logPath = '';
        
        if ($logType === 'laravel') {
            $logPath = storage_path('logs/laravel.log');
            $logs = $this->parseLaravelLog($logPath, $limit, $filter);
        } elseif ($logType === 'sms') {
            $smsLogDir = storage_path('logs/sms');
            if (File::exists($smsLogDir)) {
                $logs = $this->parseSmsLogs($smsLogDir, $limit);
            }
        }
        
        return view('admin.logs.index', compact('logs', 'logType', 'filter', 'limit'));
    }
    
    private function parseLaravelLog($logPath, $limit, $filter)
    {
        if (!File::exists($logPath)) {
            return [];
        }
        
        $content = File::get($logPath);
        $lines = explode("\n", $content);
        
        $parsedLogs = [];
        $currentLog = null;
        
        // Parse from bottom to top (most recent first)
        $lines = array_reverse($lines);
        
        foreach ($lines as $line) {
            if (empty(trim($line))) {
                continue;
            }
            
            // Match log entry pattern: [2024-12-23 10:30:45] local.ERROR: Message
            if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] \w+\.(\w+): (.+)/', $line, $matches)) {
                // If we have a current log being built, save it
                if ($currentLog !== null) {
                    if ($this->shouldIncludeLog($currentLog['level'], $filter)) {
                        $parsedLogs[] = $currentLog;
                        if (count($parsedLogs) >= $limit) {
                            break;
                        }
                    }
                }
                
                // Start a new log entry
                $currentLog = [
                    'timestamp' => $matches[1],
                    'level' => strtolower($matches[2]),
                    'message' => $matches[3],
                    'trace' => [],
                    'file' => null,
                    'line' => null,
                ];
                
                // Try to extract file and line from message
                if (preg_match('/ in ([^:]+):(\d+)/', $matches[3], $fileMatch)) {
                    $currentLog['file'] = $fileMatch[1];
                    $currentLog['line'] = $fileMatch[2];
                }
            } elseif ($currentLog !== null) {
                // This is a continuation line (stack trace, etc.)
                $trimmedLine = trim($line);
                
                // Extract file info from stack trace
                if (preg_match('/#\d+ (.+)\((\d+)\)/', $trimmedLine, $traceMatch)) {
                    $currentLog['trace'][] = $trimmedLine;
                    if ($currentLog['file'] === null) {
                        $currentLog['file'] = $traceMatch[1];
                        $currentLog['line'] = $traceMatch[2];
                    }
                } elseif (!empty($trimmedLine)) {
                    $currentLog['trace'][] = $trimmedLine;
                }
            }
        }
        
        // Don't forget the last log entry
        if ($currentLog !== null && $this->shouldIncludeLog($currentLog['level'], $filter)) {
            $parsedLogs[] = $currentLog;
        }
        
        return $parsedLogs;
    }
    
    private function parseSmsLogs($smsLogDir, $limit)
    {
        $files = File::files($smsLogDir);
        $logs = [];
        
        // Sort files by modification time (newest first)
        usort($files, function ($a, $b) {
            return File::lastModified($b) - File::lastModified($a);
        });
        
        foreach ($files as $file) {
            if (count($logs) >= $limit) {
                break;
            }
            
            $content = File::get($file->getPathname());
            $lines = explode("\n", $content);
            
            foreach ($lines as $line) {
                if (empty(trim($line))) {
                    continue;
                }
                
                $logs[] = [
                    'timestamp' => date('Y-m-d H:i:s', File::lastModified($file->getPathname())),
                    'level' => 'info',
                    'message' => $line,
                    'file' => $file->getFilename(),
                    'trace' => [],
                ];
                
                if (count($logs) >= $limit) {
                    break;
                }
            }
        }
        
        return $logs;
    }
    
    private function shouldIncludeLog($level, $filter)
    {
        if ($filter === 'all') {
            return true;
        }
        
        return $level === $filter;
    }
    
    public function clear(Request $request)
    {
        $logType = $request->input('type', 'laravel');
        
        if ($logType === 'laravel') {
            $logPath = storage_path('logs/laravel.log');
            if (File::exists($logPath)) {
                File::put($logPath, '');
            }
        } elseif ($logType === 'sms') {
            $smsLogDir = storage_path('logs/sms');
            if (File::exists($smsLogDir)) {
                File::cleanDirectory($smsLogDir);
            }
        }
        
        return redirect()->route('admin.logs.index', ['type' => $logType])
            ->with('success', 'Log files cleared successfully!');
    }
}
