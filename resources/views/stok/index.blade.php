<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dasbor Stok - {{ $franchiseName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; } </style>
</head>
<body class="p-4 md:p-8">
    <div class="container mx-auto">
        <header class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Dasbor Stok</h1>
                <p class="text-lg text-gray-600">Cabang: <strong>{{ $franchiseName }}</strong></p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg transition-colors text-sm">
                    Logout
                </button>
            </form>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            <!-- Kolom Kiri: Tabel Stok & Riwayat -->
            <div class="lg:col-span-3 space-y-8">
                <!-- Tabel Stok Terkini -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <h2 class="text-xl font-bold p-6">Stok Terkini</h2>
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Bahan Baku</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($stocks as $stock)
                                <tr class="@if($stock->quantity < 100) bg-red-50 @endif"> <!-- Contoh: beri tanda jika stok kritis -->
                                    <td class="px-5 py-4 border-b border-gray-200 text-sm">{{ $stock->ingredient->name }}</td>
                                    <td class="px-5 py-4 border-b border-gray-200 text-sm font-semibold">{{ $stock->quantity }} {{ $stock->ingredient->unit }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center py-4">Belum ada data stok.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Tabel Riwayat Permintaan -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <h2 class="text-xl font-bold p-6">Riwayat Permintaan Terakhir</h2>
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($procurements as $procurement)
                                <tr>
                                    <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">{{ $procurement->created_at->format('d M Y') }}</td>
                                    <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($procurement->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                                            @if($procurement->status == 'approved') bg-green-100 text-green-800 @endif
                                            @if($procurement->status == 'rejected') bg-red-100 text-red-800 @endif
                                            @if($procurement->status == 'completed') bg-blue-100 text-blue-800 @endif
                                        ">{{ ucfirst($procurement->status) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="text-center py-4">Belum ada riwayat permintaan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Kolom Kanan: Form Permintaan -->
            <div class="lg:col-span-2">
                <div class="bg-white p-6 rounded-lg shadow-md sticky top-8">
                    <h2 class="text-xl font-bold mb-4">Buat Permintaan Baru</h2>
                    <form action="{{ route('stok.request.store') }}" method="POST">
                        @csrf
                        <div id="request-items" class="space-y-4 max-h-[45vh] overflow-y-auto pr-2">
                            <!-- Baris Item Permintaan akan ditambahkan di sini oleh JS -->
                        </div>
                        
                        <button type="button" id="add-item-btn" class="mt-4 text-sm bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-md w-full">
                            + Tambah Item Permintaan
                        </button>

                        <div class="mt-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Catatan Tambahan</label>
                            <textarea name="notes" id="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm"></textarea>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg">
                                Kirim Permintaan ke Pusat
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const addItemBtn = document.getElementById('add-item-btn');
        const requestItemsContainer = document.getElementById('request-items');
        const ingredients = @json($ingredients);
        let itemIndex = 0;

        function createNewItemRow() {
            const itemDiv = document.createElement('div');
            itemDiv.classList.add('flex', 'gap-2', 'items-end', 'p-2', 'border', 'rounded-md', 'bg-gray-50');
            
            const optionsHtml = ingredients.map(ing => 
                `<option value="${ing.id}">${ing.name} (${ing.unit})</option>`
            ).join('');

            itemDiv.innerHTML = `
                <div class="flex-grow">
                    <label class="text-xs font-medium text-gray-600">Bahan Baku</label>
                    <select name="items[${itemIndex}][ingredient_id]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm" required>
                        <option value="">Pilih...</option>
                        ${optionsHtml}
                    </select>
                </div>
                <div class="w-2/5">
                    <label class="text-xs font-medium text-gray-600">Jumlah</label>
                    <input type="number" name="items[${itemIndex}][quantity]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm" step="any" required>
                </div>
                <button type="button" class="remove-item-btn h-10 w-8 flex-shrink-0 flex items-center justify-center bg-red-100 text-red-500 hover:bg-red-200 rounded-md" title="Hapus Item">&times;</button>
            `;

            requestItemsContainer.appendChild(itemDiv);
            itemIndex++;
        }

        // Tambahkan satu baris awal
        if (requestItemsContainer.children.length === 0) createNewItemRow();

        // Tombol untuk tambah item
        addItemBtn.addEventListener('click', createNewItemRow);

        // === BAGIAN YANG DIPERBAIKI ===
        // Menggunakan logika yang lebih sederhana dan langsung untuk menghapus
        requestItemsContainer.addEventListener('click', function(e) {
            // Cek apakah elemen yang diklik adalah tombol hapus
            if (e.target && e.target.classList.contains('remove-item-btn')) {
                // Hapus elemen induk dari tombol tersebut (yaitu seluruh baris div.flex)
                e.target.parentElement.remove();
            }
        });
    </script>
</body>
</html>
