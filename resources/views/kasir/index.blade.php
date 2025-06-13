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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Konfigurasi kustom untuk Tailwind & Font */
        body { font-family: 'Inter', sans-serif; }
        .kukurukuy-bg { background-color: #FFF8E1; }
        .kukurukuy-header { background-color: #FFC107; color: #6D4C41; }
        .kukurukuy-card { border: 2px solid #FFD54F; }
        .kukurukuy-checkout { background-color: #4CAF50; }
        .kukurukuy-checkout:hover { background-color: #45a049; }
        .product-card { transition: all 0.2s ease-in-out; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1); }
    </style>
</head>
<body class="kukurukuy-bg">

    <!-- Header Aplikasi -->
    <header class="kukurukuy-header p-4 shadow-lg sticky top-0 z-10 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold tracking-wider text-left">KukuruKuy POS</h1>
        </div>
        
        <div class="text-right">
            @auth
                <p class="font-semibold text-sm">
                    Cabang: {{ Auth::user()->franchise->name ?? 'Pusat' }}
                </p>
                <p class="font-semibold">
                    Kasir: {{ Auth::user()->name }}
                </p>
            @endauth
        </div>

        <!-- Tombol Logout -->
        <div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    Logout
                </button>
            </form>
        </div>
    </header>


    <!-- Konten Utama -->
    <main class="container mx-auto mt-4 p-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Kolom Kiri: Daftar Produk -->
            <div class="lg:col-span-2">
                <h2 class="text-2xl font-bold mb-4 text-gray-700">Pilih Menu</h2>
                <div id="product-list" class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-5">
                    <!-- Kartu Produk akan di-generate oleh JavaScript -->
                </div>
            </div>

            <!-- Kolom Kanan: Keranjang & Pembayaran -->
            <div class="lg:col-span-1">
                 <div class="bg-white p-6 rounded-xl shadow-md sticky top-32">
                    <h2 class="text-2xl font-bold mb-4 border-b pb-3 text-gray-700">Keranjang</h2>
                    <div id="cart-items" class="space-y-3 mb-4 min-h-[100px] max-h-[40vh] overflow-y-auto pr-2">
                        <!-- Item keranjang akan muncul di sini -->
                        <p id="cart-placeholder" class="text-gray-500 text-center mt-8">Keranjang masih kosong...</p>
                    </div>

                    <!-- Total & Tombol Bayar -->
                    <div class="border-t-2 border-dashed pt-4">
                        <div class="flex justify-between items-center font-bold text-xl mb-4">
                            <span class="text-gray-800">Total:</span>
                            <span id="cart-total" class="text-green-600">Rp 0</span>
                        </div>
                        <button id="checkout-button" class="w-full kukurukuy-checkout text-white py-3 rounded-lg font-bold text-lg transition-colors shadow-md disabled:bg-gray-400 disabled:cursor-not-allowed">
                            PROSES PESANAN
                        </button>
                    </div>
                 </div>
            </div>
        </div>
    </main>
    
    <!-- Modal Notifikasi -->
    <div id="notification-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-xl text-center">
            <p id="notification-message" class="text-lg mb-4"></p>
            <button onclick="document.getElementById('notification-modal').classList.add('hidden')" class="bg-blue-500 text-white px-4 py-2 rounded-md">Tutup</button>
        </div>
    </div>
    
    <!-- Elemen tersembunyi untuk menyimpan data dari Blade -->
    <div id="app-data"
         data-products="{{ $products->toJson() }}"
         data-order-url="{{ route('kasir.order.store') }}"
         hidden>
    </div>


    <script>
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
                    <div class="product-card bg-white rounded-lg shadow-sm p-3 text-center cursor-pointer flex flex-col justify-between kukurukuy-card" onclick="addToCart(${product.id})">
                        <div>
                            <img src="${imageUrl}" alt="${product.name}" onerror="this.onerror=null;this.src='https://placehold.co/300x200/FFC107/6D4C41?text=KukuruKuy';" class="mx-auto mb-2 rounded-md object-cover h-24 w-full">
                            <h3 class="font-semibold text-gray-800 text-sm">${product.name}</h3>
                        </div>
                        <p class="text-gray-600 font-bold mt-2">${formatRupiah(product.price)}</p>
                    </div>
                `;
                productListEl.innerHTML += productCard;
            });
        };
        
        const renderCart = () => {
            cartItemsEl.innerHTML = '';
            cartPlaceholderEl.style.display = cart.length === 0 ? 'block' : 'none';
            
            cart.forEach(item => {
                const cartItem = `
                    <div class="flex justify-between items-center bg-gray-50 p-2 rounded-md">
                        <div>
                            <p class="font-semibold text-sm">${item.name}</p>
                            <p class="text-xs text-gray-500">${formatRupiah(item.price)}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="w-6 h-6 bg-gray-200 rounded-full font-bold text-sm hover:bg-gray-300" onclick="updateQuantity(${item.id}, -1)">-</button>
                            <span class="font-bold w-4 text-center">${item.quantity}</span>
                            <button class="w-6 h-6 bg-yellow-400 text-white rounded-full font-bold text-sm hover:bg-yellow-500" onclick="updateQuantity(${item.id}, 1)">+</button>
                        </div>
                    </div>
                `;
                cartItemsEl.innerHTML += cartItem;
            });
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
            checkoutButton.innerText = 'MEMPROSES...';

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
                checkoutButton.innerText = 'PROSES PESANAN';
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
