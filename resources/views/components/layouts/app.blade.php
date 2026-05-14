<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Suppliers - CLT Layup</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        .font-elegant {
            font-family: 'Georgia', serif;
        }
        
        /* Drag and Drop styles */
        .dragging {
            opacity: 0.5;
            background-color: #f3f4f6;
        }
        .drag-over {
            border-top: 2px solid #3e7c5b;
        }
        .drag-handle {
            cursor: grab;
        }
        .drag-handle:active {
            cursor: grabbing;
        }
        .modal-overlay {
            transition: opacity 0.25s ease-in-out;
            opacity: 0;
            pointer-events: none;
        }
        .modal-overlay.open {
            opacity: 1;
            pointer-events: auto;
        }
        .modal-content {
            transition:
                transform 0.25s ease-in-out,
                opacity 0.25s ease-in-out;
            transform: scale(0.95) translateY(10px);
            opacity: 0;
        }
        .modal-overlay.open .modal-content {
            transform: scale(1) translateY(0);
            opacity: 1;
        }
    </style>
</head>
<body class="text-gray-800 font-sans antialiased bg-[#f8f9fa]">

    @include('components.navbar');

    {{ $slot }}
<script src="{{ asset('js/modal.js') }}"></script>
</body>
</html>