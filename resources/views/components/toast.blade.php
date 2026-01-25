@props([
    'message' => null,
    'type' => 'success',
    'duration' => 8000,
    'undoUrl' => null,
])

@php
    $sessionMessage = session('accountDeleted');
    $sessionUndo = session('accountDeletedUndo');
    $msg = $message ?? $sessionMessage;
    $undo = $undoUrl ?? $sessionUndo;
    $duration = (int) ($duration ?? 8000);
    $color = $type === 'error' ? 'bg-red-600' : 'bg-green-600';
@endphp

@if($msg)
    <div x-data="{ show: true, msg: {{ Illuminate\Support\Js::from($msg) }}, duration: {{ $duration }} }"
         x-init="setTimeout(()=> show = false, duration);"
         x-show="show" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed top-6 right-6 z-50 rounded-lg {{ $color }} text-white px-4 py-2 shadow-lg flex items-center gap-3">

        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden>
            @if($type === 'error')
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            @else
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            @endif
        </svg>

        <span x-text="msg"></span>

        @if($undo)
            <a :href="'{{ $undo }}'" class="ml-3 underline text-white text-sm">Undo</a>
        @endif

    </div>
@endif
