<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSRF Token untuk keamanan request POST Laravel -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kasir - KukuruKuy</title>
    <!-- Memuat Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Memuat Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>
        /* Konfigurasi kustom untuk Tailwind & Font */
        body { 
            font-family: 'Inter', sans-serif; 
            background: linear-gradient(135deg, #FFF8E1 0%, #FFFBF2 100%);
            min-height: 100vh;
        }
        
        .kukurukuy-header { 
            background: linear-gradient(135deg, #FFB300 0%, #FFC107 50%, #FFD54F 100%);
            color: #6D4C41; 
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 193, 7, 0.3);
        }
        
        .product-card { 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(145deg, #ffffff 0%, #fefefe 100%);
            border: 1px solid rgba(255, 213, 79, 0.2);
            position: relative;
            overflow: hidden;
        }
        
        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 193, 7, 0.1), transparent);
            transition: left 0.5s;
        }
        
        .product-card:hover::before {
            left: 100%;
        }
        
        .product-card:hover { 
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-color: #FFB300;
        }
        
        .cart-container {
            background: linear-gradient(145deg, #ffffff 0%, #fafafa 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 213, 79, 0.2);
        }
        
        .cart-item {
            background: linear-gradient(145deg, #f8f9fa 0%, #ffffff 100%);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }
        
        .cart-item:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .kukurukuy-checkout { 
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .kukurukuy-checkout::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .kukurukuy-checkout:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .kukurukuy-checkout:hover { 
            background: linear-gradient(135deg, #45a049 0%, #388e3c 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(76, 175, 80, 0.3);
        }
        
        .quantity-btn {
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        
        .quantity-btn:hover {
            transform: scale(1.1);
        }
        
        .modal-backdrop {
            backdrop-filter: blur(8px);
            background: rgba(0, 0, 0, 0.6);
        }
        
        .modal-content {
            background: linear-gradient(145deg, #ffffff 0%, #fafafa 100%);
            animation: modalSlideIn 0.3s ease-out;
        }
        
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        
        .product-image {
            transition: all 0.3s ease;
        }
        
        .product-card:hover .product-image {
            transform: scale(1.1);
        }
        
        .floating-total {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            box-shadow: 0 8px 32px rgba(76, 175, 80, 0.3);
        }
        
        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #FFB300, #FFC107);
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #FF8F00, #FFB300);
        }
    </style>
</head>
<body>

    <!-- Header Aplikasi -->
    <header class="kukurukuy-header p-6 shadow-2xl sticky top-0 z-20 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                <i data-lucide="soup" class="w-6 h-6 text-amber-800"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold tracking-wider">KukuruKuy</h1>
                <p class="text-sm font-medium opacity-80">Sistem Kasir</p>
            </div>
        </div>
        
        <!-- Tombol Logout dan Info User -->
        <div class="flex items-center gap-6">
            @auth
            <div class="text-right glass-effect p-4 rounded-xl">
                <p class="font-semibold text-sm flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                    Cabang: {{ Auth::user()->franchise->name ?? 'Pusat' }}
                </p>
                <p class="font-bold flex items-center gap-2 mt-1">
                    <i data-lucide="user" class="w-4 h-4"></i>
                    Kasir: {{ Auth::user()->name }}
                </p>
            </div>
            @endauth
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 hover:transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center gap-2">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    Logout
                </button>
            </form>
        </div>
    </header>

    <!-- Konten Utama -->
    <main class="container mx-auto mt-8 p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Kolom Kiri: Daftar Produk -->
            <div class="lg:col-span-2">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-r from-amber-400 to-orange-400 rounded-full flex items-center justify-center">
                        <i data-lucide="utensils" class="w-5 h-5 text-white"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-800">Pilih Menu</h2>
                </div>
                
                <div id="product-list" class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
                    <!-- Kartu Produk akan di-generate oleh JavaScript -->
                </div>
            </div>

            <!-- Kolom Kanan: Keranjang & Pembayaran -->
            <div class="lg:col-span-1">
                <div class="cart-container p-6 rounded-2xl shadow-2xl sticky top-32">
                    <div class="flex items-center gap-3 mb-6 border-b border-gray-200 pb-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-green-400 to-blue-400 rounded-full flex items-center justify-center">
                            <i data-lucide="shopping-cart" class="w-5 h-5 text-white"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800">Keranjang</h2>
                    </div>
                    
                    <div id="cart-items" class="space-y-3 mb-6 min-h-[120px] max-h-[40vh] overflow-y-auto pr-2 custom-scrollbar">
                        <!-- Item keranjang akan muncul di sini -->
                        <div id="cart-placeholder" class="text-center mt-12 pulse-animation">
                            <i data-lucide="shopping-bag" class="w-16 h-16 text-gray-300 mx-auto mb-3"></i>
                            <p class="text-gray-500 font-medium">Keranjang masih kosong...</p>
                            <p class="text-gray-400 text-sm mt-1">Pilih menu untuk memulai</p>
                        </div>
                    </div>

                    <!-- Total & Tombol Bayar -->
                    <div class="border-t-2 border-dashed border-gray-200 pt-6">
                        <div class="floating-total p-4 rounded-xl mb-4 text-white">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-lg flex items-center gap-2">
                                    <i data-lucide="calculator" class="w-5 h-5"></i>
                                    Total Pembayaran:
                                </span>
                                <span id="cart-total" class="font-bold text-2xl">Rp 0</span>
                            </div>
                        </div>
                        
                        <button id="checkout-button" class="w-full kukurukuy-checkout text-white py-4 rounded-xl font-bold text-lg transition-all duration-300 shadow-xl disabled:bg-gray-400 disabled:cursor-not-allowed relative z-10 flex items-center justify-center gap-3">
                            <i data-lucide="credit-card" class="w-5 h-5"></i>
                            <span>PROSES PESANAN</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Modal Notifikasi -->
    <div id="notification-modal" class="hidden fixed inset-0 modal-backdrop z-50 flex justify-center items-center">
        <div class="modal-content p-8 rounded-2xl shadow-2xl text-center max-w-md mx-4">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="check-circle" class="w-8 h-8 text-green-500"></i>
            </div>
            <p id="notification-message" class="text-lg mb-6 text-gray-800 font-medium"></p>
            <button onclick="document.getElementById('notification-modal').classList.add('hidden')" class="bg-gradient-to-r from-blue-500 to-purple-500 text-white px-8 py-3 rounded-xl font-semibold hover:from-blue-600 hover:to-purple-600 transition-all duration-300 hover:transform hover:scale-105">
                Tutup
            </button>
        </div>
    </div>
    
    <!-- Elemen tersembunyi untuk menyimpan data dari Blade -->
    <div id="app-data"
        data-products="{{ $products->toJson() }}"
        data-order-url="{{ route('kasir.order.store') }}"
        hidden>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        // --- Elemen Data dari Laravel ---
        const appDataEl = document.getElementById('app-data');
        const products = JSON.parse(appDataEl.dataset.products);
        const orderUrl = appDataEl.dataset.orderUrl;

        // --- STATE ---
        let cart = []; 

        // --- ELEMEN DOM ---
        const productListEl = document.getElementById('product-list');
        const cartItemsEl = document.getElementById('cart-items');
        const cartTotalEl = document.getElementById('cart-total');
        const cartPlaceholderEl = document.getElementById('cart-placeholder');
        const checkoutButton = document.getElementById('checkout-button');
        const notificationModal = document.getElementById('notification-modal');
        const notificationMessage = document.getElementById('notification-message');

        // --- FUNGSI ---

        const showNotification = (message) => {
            notificationMessage.textContent = message;
            notificationModal.classList.remove('hidden');
        };

        const formatRupiah = (number) => {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency', currency: 'IDR', minimumFractionDigits: 0
            }).format(number);
        };

        const renderProducts = () => {
            productListEl.innerHTML = '';
            products.forEach(product => {
                const imageUrl = product.image_url ? `/storage/${product.image_url}` : 'https://placehold.co/300x200/FFC107/6D4C41?text=KukuruKuy';
                const productCard = `
                    <div class="product-card bg-white rounded-2xl shadow-lg p-4 text-center cursor-pointer flex flex-col justify-between group" onclick="addToCart(${product.id})">
                        <div class="relative overflow-hidden rounded-xl mb-3">
                            <img src="${imageUrl}" alt="${product.name}" onerror="this.onerror=null;this.src='https://placehold.co/300x200/FFC107/6D4C41?text=KukuruKuy';" class="product-image mx-auto rounded-xl object-cover h-32 w-full">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                        <div class="flex-grow flex flex-col justify-between">
                            <h3 class="font-bold text-gray-800 text-sm mb-2 line-clamp-2">${product.name}</h3>
                            <div class="flex items-center justify-between">
                                <p class="text-amber-600 font-bold text-lg">${formatRupiah(product.price)}</p>
                                <div class="w-8 h-8 bg-amber-400 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 transform group-hover:scale-110">
                                    <i data-lucide="plus" class="w-4 h-4 text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                productListEl.innerHTML += productCard;
            });
            // Re-initialize icons for new elements
            lucide.createIcons();
        };
        
        const renderCart = () => {
            cartItemsEl.innerHTML = '';
            cartPlaceholderEl.style.display = cart.length === 0 ? 'block' : 'none';
            
            cart.forEach(item => {
                const cartItem = `
                    <div class="cart-item flex justify-between items-center p-4 rounded-xl">
                        <div class="flex-grow">
                            <p class="font-bold text-gray-800 text-sm">${item.name}</p>
                            <p class="text-amber-600 font-semibold text-sm">${formatRupiah(item.price)}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <button class="quantity-btn w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-full font-bold text-sm flex items-center justify-center" onclick="updateQuantity(${item.id}, -1)">
                                <i data-lucide="minus" class="w-3 h-3"></i>
                            </button>
                            <span class="font-bold w-8 text-center bg-gray-100 py-1 px-2 rounded-lg">${item.quantity}</span>
                            <button class="quantity-btn w-8 h-8 bg-green-100 hover:bg-green-200 text-green-600 rounded-full font-bold text-sm flex items-center justify-center" onclick="updateQuantity(${item.id}, 1)">
                                <i data-lucide="plus" class="w-3 h-3"></i>
                            </button>
                        </div>
                    </div>
                `;
                cartItemsEl.innerHTML += cartItem;
            });
            // Re-initialize icons for new elements
            lucide.createIcons();
            updateTotal();
        };

        const addToCart = (productId) => {
            const product = products.find(p => p.id === productId);
            const itemInCart = cart.find(item => item.id === productId);

            if (itemInCart) {
                itemInCart.quantity++;
            } else {
                cart.push({ ...product, quantity: 1 });
            }
            renderCart();
        };

        const updateQuantity = (productId, change) => {
            const itemInCart = cart.find(item => item.id === productId);
            if (!itemInCart) return;

            itemInCart.quantity += change;
            if (itemInCart.quantity <= 0) {
                cart = cart.filter(item => item.id !== productId);
            }
            renderCart();
        };

        const updateTotal = () => {
            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            cartTotalEl.innerText = formatRupiah(total);
            checkoutButton.disabled = cart.length === 0;
        };

        const processCheckout = async () => {
            if (cart.length === 0) return;
            checkoutButton.disabled = true;
            checkoutButton.innerHTML = `
                <div class="flex items-center justify-center gap-3">
                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>
                    <span>MEMPROSES...</span>
                </div>
            `;

            const cartData = cart.map(item => ({ id: item.id, quantity: item.quantity }));
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch(orderUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ cart: cartData })
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Gagal memproses pesanan.');
                }
                
                showNotification(`Pesanan dengan ${result.invoice} berhasil dibuat!`);
                cart = []; // Kosongkan keranjang
                renderCart(); // Render ulang tampilan

            } catch (error) {
                console.error('Checkout error:', error);
                showNotification(`Error: ${error.message}`);
            } finally {
                checkoutButton.disabled = false;
                checkoutButton.innerHTML = `
                    <i data-lucide="credit-card" class="w-5 h-5"></i>
                    <span>PROSES PESANAN</span>
                `;
                lucide.createIcons();
            }
        };

        // --- INISIALISASI ---
        checkoutButton.addEventListener('click', processCheckout);

        document.addEventListener('DOMContentLoaded', () => {
            renderProducts();
            renderCart();
        });
    </script>
</body>
</html>