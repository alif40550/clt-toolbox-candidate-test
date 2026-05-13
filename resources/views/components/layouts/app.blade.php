<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppliers - CLT Layup</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
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

</body>
</html>