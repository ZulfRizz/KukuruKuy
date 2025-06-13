<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak - KukuruKuy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #fef3c7; }
    </style>
</head>
<body class="flex items-center justify-center h-screen">
    <div class="text-center bg-white p-10 rounded-lg shadow-2xl max-w-md">
        <svg class="mx-auto h-16 w-16 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
        </svg>

        <h1 class="mt-4 text-3xl font-bold text-gray-800">Akses Tidak Diizinkan</h1>
        <p class="mt-2 text-gray-600">
            Akun Anda tidak terikat pada cabang (franchise) manapun. Halaman ini hanya untuk pengguna yang memiliki cabang, seperti Manajer atau Kasir.
        </p>

        @if(Auth::user()->role === 'admin')
            <p class="mt-4 text-sm text-yellow-800 bg-yellow-100 p-3 rounded-md">
                Sebagai <strong>Admin</strong>, Anda dapat mengelola semua data melalui Panel Admin (BIOS).
            </p>
            <a href="http://app.kukurukuy.test" class="mt-6 inline-block w-full rounded-md bg-yellow-500 px-4 py-2 font-semibold text-white shadow-sm hover:bg-yellow-600">
                Buka Panel Admin
            </a>
        @else
            <p class="mt-4 text-sm text-red-700 bg-red-100 p-3 rounded-md">
                Silakan hubungi administrator untuk mendapatkan akses ke cabang.
            </p>
        @endif

    </div>
</body>
</html>
