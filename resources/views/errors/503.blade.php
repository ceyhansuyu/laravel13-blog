<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sitemiz Bakımda | Mini Blog</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts: Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }
        @keyframes pulse-slow {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
        }
        .animate-pulse-slow {
            animation: pulse-slow 3s ease-in-out infinite;
        }
        .title-gradient {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-6">

    <div class="max-w-xl w-full text-center">
        <!-- İkon -->
        <div class="mb-8 flex justify-center">
            <div class="relative">
                <div class="absolute inset-0 bg-blue-200 rounded-full blur-xl opacity-50 animate-pulse-slow"></div>
                <div class="relative bg-white p-6 rounded-full shadow-xl border border-gray-100">
                    <svg class="w-16 h-16 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Başlık -->
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mb-6 title-gradient">
            {{ __('Under Construction') }}
        </h1>

        <!-- Dinamik Mesaj -->
        <p class="text-lg md:text-xl text-gray-600 mb-8 leading-relaxed">
            {{ $exception->getMessage() ?: 'Daha iyi bir deneyim sunabilmek için şu anda sitemizde güncelleme çalışmaları yapıyoruz.' }}
        </p>

        <!-- Alt Bilgi / Çizgi -->
        <div class="flex items-center justify-center space-x-2">
            <div class="h-1 w-12 bg-blue-500 rounded-full"></div>
            <div class="h-1 w-3 bg-blue-300 rounded-full"></div>
            <div class="h-1 w-3 bg-blue-200 rounded-full"></div>
        </div>

        <div class="mt-10 text-sm text-gray-400">
            Mini Blog &copy; {{ date('Y') }}
        </div>
    </div>

</body>
</html>
