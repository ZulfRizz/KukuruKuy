<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dasbor Stok - {{ $franchiseName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
      body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800">
    <div class="container mx-auto max-w-7xl p-4 sm:p-6 lg:p-8">
        
        <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Dasbor Stok</h1>
                <p class="mt-1 text-base text-slate-600">Cabang: <strong class="font-semibold">{{ $franchiseName }}</strong></p>
            </div>
            <div class="flex items-center gap-4 mt-4 sm:mt-0">
                <span class="text-sm font-medium text-slate-700 hidden sm:block">
                    Halo, <strong class="font-semibold">{{ auth()->user()->name ?? 'Pengguna' }}</strong>
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 font-semibold py-2 px-4 rounded-lg transition-colors duration-200 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </header>

        <main class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            
            <div class="lg:col-span-3 space-y-8">
                
                <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-lg font-semibold text-slate-900">Stok Terkini</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Bahan Baku</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Jumlah Stok</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @forelse ($stocks as $stock)
                                    <tr class="@if($stock->quantity < 100) bg-red-50/50 @endif">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-800">{{ $stock->ingredient->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 font-semibold">{{ $stock->quantity }} {{ $stock->ingredient->unit }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-10 text-slate-500">Belum ada data stok.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-lg font-semibold text-slate-900">Riwayat Permintaan Terakhir</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @forelse ($procurements as $procurement)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $procurement->created_at->format('d M Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($procurement->status == 'pending') bg-yellow-100 text-yellow-800 @endif
                                                @if($procurement->status == 'approved') bg-green-100 text-green-800 @endif
                                                @if($procurement->status == 'rejected') bg-red-100 text-red-800 @endif
                                                @if($procurement->status == 'completed') bg-sky-100 text-sky-800 @endif
                                            ">{{ ucfirst($procurement->status) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-10 text-slate-500">Belum ada riwayat permintaan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-200 sticky top-8">
                    <h2 class="text-lg font-semibold mb-5 text-slate-900">Buat Permintaan Baru</h2>
                    <form action="{{ route('stok.request.store') }}" method="POST">
                        @csrf
                        <div id="request-items-container" class="space-y-4 max-h-[45vh] overflow-y-auto pr-3 -mr-3">
                            </div>
                        
                        <button type="button" id="add-item-btn" class="mt-4 flex items-center justify-center gap-2 text-sm bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold py-2 px-4 rounded-lg w-full transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                            Tambah Item
                        </button>

                        <div class="mt-6">
                            <label for="notes" class="block text-sm font-medium text-slate-700">Catatan Tambahan (Opsional)</label>
                            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition"></textarea>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition-colors duration-200 shadow-sm hover:shadow-md">
                                Kirim Permintaan ke Pusat
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addItemBtn = document.getElementById('add-item-btn');
            const requestItemsContainer = document.getElementById('request-items-container');
            // Data ingredients dari controller Laravel
            const ingredients = @json($ingredients);
            let itemIndex = 0;

            function createNewItemRow() {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'request-item-row grid grid-cols-12 gap-3 items-center bg-slate-50 p-3 rounded-lg border border-slate-200';
                
                const selectOptions = ingredients.map(ing => 
                    `<option value="${ing.id}">${ing.name} (${ing.unit})</option>`
                ).join('');

                itemDiv.innerHTML = `
                    <div class="col-span-12 sm:col-span-6">
                        <label class="text-xs font-medium text-slate-600">Bahan Baku</label>
                        <select name="items[${itemIndex}][ingredient_id]" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition" required>
                            <option value="">Pilih...</option>
                            ${selectOptions}
                        </select>
                    </div>
                    <div class="col-span-7 sm:col-span-4">
                        <label class="text-xs font-medium text-slate-600">Jumlah</label>
                        <input type="number" name="items[${itemIndex}][quantity]" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition" step="any" min="0" required>
                    </div>
                    <div class="col-span-5 sm:col-span-2 self-end">
                        <button type="button" class="remove-item-btn w-full h-10 flex items-center justify-center bg-red-100 text-red-600 hover:bg-red-200 rounded-md transition-colors duration-200" title="Hapus Item">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                `;

                requestItemsContainer.appendChild(itemDiv);
                itemIndex++;
            }

            // Tambahkan satu baris item secara otomatis saat halaman dimuat
            if (requestItemsContainer.children.length === 0) {
                createNewItemRow();
            }

            // Event listener untuk tombol 'Tambah Item'
            addItemBtn.addEventListener('click', createNewItemRow);

            // Event listener untuk menghapus baris item (menggunakan event delegation)
            requestItemsContainer.addEventListener('click', function(e) {
                // Cari tombol hapus terdekat dari elemen yang di-klik
                const removeBtn = e.target.closest('.remove-item-btn');
                
                if (removeBtn) {
                    // Cari parent row terdekat dan hapus
                    const rowToRemove = removeBtn.closest('.request-item-row');
                    if (rowToRemove) {
                        rowToRemove.remove();
                    }
                }
            });
        });
    </script>
</body>
</html>