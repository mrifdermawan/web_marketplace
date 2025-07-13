<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laravel Marketplace</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>


    <style>
        #background-container {
            position: relative;
            z-index: 0;
        }
       #background-container::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.5;
            z-index: -1;
            transition: background-image 1s ease-in-out;
        }
        body {
            font-family: 'Poppins', sans-serif;
        }
        .product-card {
            border: 1px solid #e5e7eb;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fade-in-up 0.3s ease-out;
        }
        @keyframes blink {
            0%, 80%, 100% { opacity: 0; }
            40% { opacity: 1; }
        }
        .dot-one, .dot-two, .dot-three {
            animation: blink 1.4s infinite;
        }
        .dot-two { animation-delay: 0.2s; }
        .dot-three { animation-delay: 0.4s; }
    </style>
</head>
<body id="background-container" class="relative transition-all duration-1000 bg-cover bg-center bg-no-repeat min-h-screen">

     <!-- 🔁 Background overlay -->
    <div id="bg-overlay"
        class="fixed inset-0 bg-cover bg-center opacity-60 z-[-1]"
        style="background-image: url('/images/backgrounds/bg9.png');">
    </div>

    {{-- Chat Button --}}
    <div id="chatbot-button" class="fixed bottom-6 right-6 z-50">
        <button id="open-chat" class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-full shadow-lg text-xl">
            💬
        </button>
    </div>

    {{-- Chat Window --}}
    <div id="chatbot-window"
     class="fixed bottom-20 right-6 w-[500px] h-[500px] bg-white shadow-lg rounded-xl border border-gray-200 hidden flex flex-col z-50 transition-all duration-300 transform scale-95">

    <!-- HEADER -->
    <div class="bg-blue-600 text-white px-4 py-2 rounded-t-xl font-semibold flex justify-between items-center">
        <span>AI Chatbot</span>
        <button id="close-chat">&times;</button>
    </div>

    <!-- ISI CHAT MESSAGE (SCROLLABLE) -->
    <div id="chat-messages" class="flex-1 overflow-y-auto px-4 py-3 text-xl space-y-2">
        <p class="text-gray-500 text-center">Halo! Ada yang bisa saya bantu?</p>
    </div>

    <!-- AI TYPING -->
    <div id="ai-typing" class="px-4 pb-2 text-left text-gray-400 text-sm italic hidden">
        AI sedang mengetik<span class="dot-one">.</span><span class="dot-two">.</span><span class="dot-three">.</span>
    </div>

    <!-- FORM INPUT -->
    <form id="chat-form" class="border-t border-gray-200 flex px-3 py-2 gap-2">
        <input type="text" id="chat-input"
               class="flex-1 px-3 py-2 text-xl border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
               placeholder="Ketik pesan...">
        <button type="submit"
                class="bg-blue-600 text-white text-sm font-semibold px-4 py-2 rounded-md hover:bg-blue-700 transition">
            Kirim
        </button>
    </form>
</div>


    {{-- Navbar --}}
    <nav class="bg-white border-b border-gray-200 shadow-sm fixed top-0 w-full z-[999]">
    <div class="w-full px-4 sm:px-6 lg:px-8 py-6 flex justify-between items-center">
        <div class="flex items-center gap-8 flex-wrap">
            @auth
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition font-semibold text-base md:text-2xl">Semua Produk</a>
                @if (Auth::user()->role === 'seller')
                    <a href="{{ route('products.create') }}" class="hover:text-blue-600 transition font-semibold text-base md:text-2xl">Tambah Produk</a>
                @endif
                @php $user = Auth::user(); @endphp
                @if (!$user || $user->role !== 'seller')
                    <a href="{{ route('kategori.index') }}" class="hover:text-blue-600 transition font-semibold text-base md:text-2xl flex items-center">
                        🏱 <span class="ml-1">Kategori</span>
                    </a>
                @endif
                <form action="{{ route('search') }}" method="GET" class="flex items-center bg-white border-2 border-blue-200 rounded-xl overflow-hidden shadow-md w-72 md:w-80">
                    <input
                        type="text"
                        name="query"
                        value="{{ request('query') }}"
                        class="flex-1 px-4 py-2 text-base text-gray-700 focus:outline-none focus:ring-0"
                        placeholder="🔍 Cari produk..."
                    >
                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 transition duration-200"
                    >
                        Cari
                    </button>
                </form>

            @else
                <a href="{{ route('home') }}" class="hover:text-blue-600 transition font-semibold text-base md:text-2xl">Home</a>
            @endauth
        </div>
        <div class="flex items-center gap-4 text-base md:text-xl font-medium">
            @auth
                <a href="{{ route('profile') }}" class="hover:text-blue-600 transition font-semibold flex items-center gap-1 mr-6">
                    👤 <span>Profil {{ Auth::user()->name }} <span class="text-xs text-gray-500">[{{ Auth::user()->role }}]</span></span>
                </a>


                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="hover:text-red-500 transition font-semibold">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="hover:text-blue-600 transition font-semibold">Login</a>
                <a href="{{ route('register') }}" class="hover:text-blue-600 transition font-semibold">Register</a>
            @endauth
        </div>
    </div>
</nav>


   {{-- ✅ NOTIF SUKSES --}}
@if (session('success'))
    <div id="flash-success" class="fixed top-24 left-1/2 transform -translate-x-1/2 z-50">
        <div class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg text-lg text-center max-w-md mx-auto">
            ✅ <span>{{ session('success') }}</span>
        </div>
    </div>
@endif


{{-- ❌ NOTIF ERROR --}}
@if (session('error'))
    <div class="fixed top-[100px] left-1/2 transform -translate-x-1/2 z-50">
        <div class="bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg text-center text-lg font-semibold animate-fade-in-up">
            ❌ {{ session('error') }}
        </div>
    </div>
@endif


    {{-- Content --}}
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6 pt-32">
        @yield('content')
    </div>

    {{-- Pagination --}}
{{-- @isset($products)
    @if (isset($products) && $products instanceof \Illuminate\Pagination\LengthAwarePaginator && $products->hasPages())
        <div class="flex justify-center mt-10">
            {{ $products->links() }}
        </div>
    @endif
@endisset --}}



    <script>
        document.addEventListener('DOMContentLoaded', () => {
        const bgOverlay = document.getElementById('bg-overlay');
        const openBtn = document.getElementById('open-chat');
        const closeBtn = document.getElementById('close-chat');
        const chatWindow = document.getElementById('chatbot-window');
        const chatForm = document.getElementById('chat-form');
        const chatInput = document.getElementById('chat-input');
        const chatMessages = document.getElementById('chat-messages');
        const typingIndicator = document.getElementById('ai-typing');

            openBtn?.addEventListener('click', () => {
                chatWindow.classList.remove('hidden', 'scale-95');
                chatWindow.classList.add('scale-100');
            });

            closeBtn?.addEventListener('click', () => {
                chatWindow.classList.remove('scale-100');
                chatWindow.classList.add('scale-95');
                setTimeout(() => chatWindow.classList.add('hidden'), 300);
            });

            chatForm?.addEventListener('submit', async (e) => {
                e.preventDefault();
                const message = chatInput.value.trim();
                if (!message) return;

                const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                chatMessages.insertAdjacentHTML('beforeend', `
                    <div class="flex justify-end animate-fade-in-up">
                        <div class="text-right max-w-[75%] break-words">
                            <div class="bg-blue-600 text-white px-4 py-2 rounded-2xl rounded-br-none shadow">
                                <p class="text-xl leading-relaxed text-left">${message}</p>
                            </div>
                            <div class="text-xs text-gray-400 mt-1">${time}</div>
                        </div>
                    </div>`);

                chatInput.value = '';
                typingIndicator.classList.remove('hidden');
                chatMessages.scrollTop = chatMessages.scrollHeight;

                try {
                    const res = await fetch("/chatbot", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ message })
                    });

                    const data = await res.json();
                    typingIndicator.classList.add('hidden');

                    chatMessages.insertAdjacentHTML('beforeend', `
                        <div class="flex items-start">
                            <div class="bg-gray-200 text-gray-800 px-4 py-2 max-w-[75%] rounded-2xl rounded-bl-none shadow">
                                <p>${data.reply}</p>
                            </div>
                        </div>`);
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                } catch (err) {
                    typingIndicator.classList.add('hidden');
                    chatMessages.insertAdjacentHTML('beforeend', `<div class="text-left text-red-500">⚠️ Error menghubungi AI</div>`);
                }
            });
        });

         document.addEventListener('DOMContentLoaded', () => {
        const flash = document.getElementById('flash-success');
        if (flash) {
            setTimeout(() => {
                flash.style.transition = 'opacity 0.5s ease';
                flash.style.opacity = '0';
                setTimeout(() => flash.remove(), 500); // remove dari DOM
            },3000); // 3 detik
        }
    });
    </script>
    <script src="//unpkg.com/alpinejs" defer></script>


</body>
</html>
