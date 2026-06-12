<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Scan QR Meja - Cafe Momoo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
        body { background: #faf9f7; color: #111827; min-height: 100dvh; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen px-5">

    <div class="w-full max-w-sm text-center">

        {{-- Logo --}}
        <div class="mb-4">
            <img src="{{ asset('logo.png') }}" alt="Logo Momo"
                 class="w-28 h-28 object-contain mx-auto mb-3">

            <h1 class="text-2xl font-bold text-gray-900 mb-2">Scan QR Meja Dulu, Yuk!</h1>
            <p class="text-gray-500 text-sm leading-relaxed">
                Untuk memesan, silakan scan QR Code yang tersedia di meja Anda terlebih dahulu.
            </p>
        </div>

        {{-- Alert pesan error / info --}}
        @if (session('error'))
            <div class="mb-4 px-4 py-3 rounded-2xl text-sm text-red-700 text-left"
                 style="background:#fef2f2; border:1px solid #fecaca;">
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-12a1 1 0 011 1v4a1 1 0 11-2 0V7a1 1 0 011-1zm0 8a1 1 0 110-2 1 1 0 010 2z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        {{-- Instruksi --}}
        <div class="space-y-3 mb-4 text-left">
            <div class="flex items-start gap-3 p-4 bg-white rounded-2xl border border-orange-100">
                <span class="flex-shrink-0 w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold"
                      style="background:#f97316;">1</span>
                <div>
                    <p class="text-sm font-semibold text-gray-800">Buka kamera ponsel Anda</p>
                    <p class="text-xs text-gray-500 mt-0.5">Gunakan kamera bawaan atau aplikasi scanner QR</p>
                </div>
            </div>
            <div class="flex items-start gap-3 p-4 bg-white rounded-2xl border border-orange-100">
                <span class="flex-shrink-0 w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold"
                      style="background:#f97316;">2</span>
                <div>
                    <p class="text-sm font-semibold text-gray-800">Arahkan ke QR Code di meja</p>
                    <p class="text-xs text-gray-500 mt-0.5">QR Code tersedia di atas meja atau di menu fisik</p>
                </div>
            </div>
            <div class="flex items-start gap-3 p-4 bg-white rounded-2xl border border-orange-100">
                <span class="flex-shrink-0 w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold"
                      style="background:#f97316;">3</span>
                <div>
                    <p class="text-sm font-semibold text-gray-800">Ikuti tautan yang muncul</p>
                    <p class="text-xs text-gray-500 mt-0.5">Anda akan langsung diarahkan ke menu pemesanan</p>
                </div>
            </div>
        </div>

    </div>

</body>
</html>