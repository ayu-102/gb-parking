<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Login - GB PARKING PAYROLL' }}</title>

    <!-- Tailwind CSS CDN (Memastikan Style Langsung Keluar) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- FontAwesome & Font -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-100 text-slate-800 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-100">
        <div
            class="w-full sm:max-w-md mt-6 px-8 py-8 bg-white shadow-xl shadow-slate-200/50 sm:rounded-2xl border border-slate-100">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
