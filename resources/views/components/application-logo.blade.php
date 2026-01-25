<svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
    <!-- Modern SMS/Message Icon -->
    <defs>
        <linearGradient id="msgGradient" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#6366f1;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#8b5cf6;stop-opacity:1" />
        </linearGradient>
    </defs>
    
    <!-- Message bubble -->
    <path fill="url(#msgGradient)" d="M54 8H10C6.7 8 4 10.7 4 14v24c0 3.3 2.7 6 6 6h6v8c0 0.8 0.5 1.5 1.2 1.8 0.3 0.1 0.5 0.2 0.8 0.2 0.5 0 1-0.2 1.4-0.6l10.8-9.4H54c3.3 0 6-2.7 6-6V14C60 10.7 57.3 8 54 8z"/>
    
    <!-- Message lines -->
    <rect x="14" y="18" width="24" height="3" rx="1.5" fill="white" opacity="0.9"/>
    <rect x="14" y="26" width="36" height="3" rx="1.5" fill="white" opacity="0.9"/>
    <rect x="14" y="34" width="28" height="3" rx="1.5" fill="white" opacity="0.9"/>
    
    <!-- Small notification dot -->
    <circle cx="50" cy="14" r="4" fill="#ef4444"/>
</svg>
