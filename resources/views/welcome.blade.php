<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Poliklinik</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/daisyui@5/daisyui.css" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 font-sans text-slate-900 antialiased dark:bg-slate-950 dark:text-slate-100">
        <main class="mx-auto max-w-5xl px-6 py-12 sm:px-8">
            <header class="mb-8 flex items-center gap-3 text-teal-700 dark:text-teal-400">
                <i class="fa-solid fa-house-medical text-2xl" aria-hidden="true"></i>
                <h1 class="text-xl font-bold">Poliklinik</h1>
            </header>
            <section aria-labelledby="welcome-heading" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 dark:border-slate-800 dark:bg-slate-900">
                <div class="mb-8">
                    <h2 id="welcome-heading" class="text-2xl font-bold">Selamat Datang</h2>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                        {{ now()->locale('id')->translatedFormat('l, d F Y') }} - Berikut ringkasan aktivitas anda hari ini
                    </p>
                </div>
                <p class="text-sm leading-6 text-slate-600 dark:text-slate-400">
                    Selamat datang di aplikasi Poliklinik.
                </p>
                <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="btn-primary mt-6">{{ auth()->check() ? 'Buka Poliklinik' : 'Masuk ke Poliklinik' }} <span aria-hidden="true">→</span></a>
            </section>
        </main>
    </body>
</html>
