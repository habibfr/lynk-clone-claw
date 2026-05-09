<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $profile->display_name ?? $profile->username }} - Links</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <!-- Profile Card -->
        <div class="bg-white rounded-3xl shadow-2xl p-8 mb-6">
            <!-- Avatar -->
            <div class="flex flex-col items-center mb-6">
                @if($profile->avatar)
                    <img src="{{ $profile->avatar }}" alt="{{ $profile->display_name }}" 
                         class="w-24 h-24 rounded-full mb-4 border-4 border-purple-500">
                @else
                    <div class="w-24 h-24 rounded-full mb-4 bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-3xl font-bold">
                        {{ strtoupper(substr($profile->username, 0, 1)) }}
                    </div>
                @endif
                
                <h1 class="text-2xl font-bold text-gray-900 mb-2">
                    {{ $profile->display_name ?? '@' . $profile->username }}
                </h1>
                
                @if($profile->bio)
                    <p class="text-gray-600 text-center max-w-md">
                        {{ $profile->bio }}
                    </p>
                @endif
            </div>

            <!-- Links -->
            <div class="space-y-4">
                @forelse($profile->links as $link)
                    <a href="{{ route('link.redirect', $link->id) }}" 
                       target="_blank"
                       class="block w-full bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-semibold py-4 px-6 rounded-xl shadow-lg transform transition hover:scale-105 hover:shadow-xl">
                        <div class="flex items-center justify-center gap-3">
                            @if($link->icon)
                                <i class="{{ $link->icon }}"></i>
                            @endif
                            <span>{{ $link->title }}</span>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        No links yet
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center text-white text-sm">
            <p>Powered by LynkClone</p>
        </div>
    </div>
</body>
</html>
