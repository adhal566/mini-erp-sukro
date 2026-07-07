@extends('layouts.app')

@section('title', 'Point of Sales')
@section('header', 'Kasir (Point of Sales)')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-[calc(100vh-10rem)]" x-data="posSystem()">
    <!-- Daftar Produk -->
    <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden flex flex-col">
        <div class="p-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-semibold text-gray-800">Pilih Produk</h3>
            <input type="text" x-model="searchQuery" placeholder="Cari produk..." class="px-4 py-2 border border-gray-300 rounded-lg text-sm outline-none focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div class="p-4 flex-1 overflow-y-auto">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <template x-for="p in filteredProduk" :key="p.id">
                    <div @click="addToCart(p)" class="border border-gray-200 rounded-lg p-4 cursor-pointer hover:border-blue-500 hover:shadow-md transition bg-gray-50 flex flex-col justify-between h-32">
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm" x-text="p.nama_produk"></h4>
                            <p class="text-xs text-gray-500 mt-1">Stok: <span x-text="p.stok_produk"></span></p>
                        </div>
                        <p class="font-semibold text-blue-600">Rp <span x-text="formatRupiah(p.harga_jual)"></span></p>
                    </div>
                </template>
                <div x-show="filteredProduk.length === 0" class="col-span-full py-8 text-center text-gray-400">
                    Produk tidak ditemukan.
                </div>
            </div>
        </div>
    </div>

    <!-- Keranjang & Checkout -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 flex flex-col overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-semibold text-gray-800">Detail Pesanan</h3>
        </div>
        
        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto p-4 space-y-3">
            <template x-for="(item, index) in cart" :key="item.id">
                <div class="flex justify-between items-center p-3 border border-gray-100 rounded-lg bg-white">
                    <div class="flex-1">
                        <h5 class="font-semibold text-sm text-gray-800" x-text="item.nama_produk"></h5>
                        <p class="text-xs text-blue-600 font-medium">Rp <span x-text="formatRupiah(item.harga_jual)"></span></p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button @click="decreaseQty(index)" class="w-6 h-6 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center hover:bg-gray-300">-</button>
                        <span class="w-6 text-center text-sm font-medium" x-text="item.qty"></span>
                        <button @click="increaseQty(index)" class="w-6 h-6 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center hover:bg-gray-300">+</button>
                    </div>
                </div>
            </template>
            <div x-show="cart.length === 0" class="text-center py-8 text-gray-400 text-sm">
                Keranjang masih kosong
            </div>
        </div>

        <!-- Checkout Area -->
        <div class="p-4 border-t border-gray-200 bg-gray-50">
            <div class="flex justify-between mb-4">
                <span class="font-semibold text-gray-600">Total Pembayaran</span>
                <span class="font-bold text-xl text-gray-800">Rp <span x-text="formatRupiah(totalAmount)"></span></span>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-500 mb-2 uppercase">Metode Pembayaran</label>
                <div class="grid grid-cols-2 gap-2">
                    <button @click="paymentMethod = 'cash'" :class="{'bg-blue-600 text-white border-blue-600': paymentMethod === 'cash', 'bg-white text-gray-600 border-gray-300': paymentMethod !== 'cash'}" class="py-2 border rounded-md text-sm font-medium transition">Cash</button>
                    <button @click="paymentMethod = 'qris'" :class="{'bg-purple-600 text-white border-purple-600': paymentMethod === 'qris', 'bg-white text-gray-600 border-gray-300': paymentMethod !== 'qris'}" class="py-2 border rounded-md text-sm font-medium transition flex items-center justify-center">
                        <span class="mr-1">📱</span> QRIS
                    </button>
                </div>
            </div>

            <button @click="processCheckout()" :disabled="cart.length === 0 || isProcessing" :class="{'opacity-50 cursor-not-allowed': cart.length === 0 || isProcessing}" class="w-full py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition flex justify-center items-center">
                <span x-show="!isProcessing">Proses Pembayaran</span>
                <span x-show="isProcessing">Memproses...</span>
            </button>
        </div>
    </div>

    <!-- Modal QRIS Simulasi -->
    <div x-show="showQrisModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
        <div class="bg-white rounded-xl shadow-xl w-96 overflow-hidden">
            <div class="bg-purple-600 p-4 text-center">
                <h3 class="text-white font-bold text-lg">Scan QRIS (Simulasi)</h3>
            </div>
            <div class="p-6 flex flex-col items-center">
                <div class="w-48 h-48 bg-gray-100 flex items-center justify-center rounded-lg border-2 border-dashed border-gray-300 mb-4">
                    <!-- Dummy QR Code UI -->
                    <div class="w-40 h-40 bg-black opacity-80 grid grid-cols-5 grid-rows-5 gap-1 p-1">
                        <!-- Pola QR dummy -->
                        <div class="col-span-1 row-span-1 bg-white"></div><div class="col-span-3 row-span-1 bg-white"></div><div class="col-span-1 row-span-1 bg-white"></div>
                        <div class="col-span-1 row-span-3 bg-white"></div><div class="col-span-3 row-span-3"></div><div class="col-span-1 row-span-3 bg-white"></div>
                        <div class="col-span-1 row-span-1 bg-white"></div><div class="col-span-3 row-span-1 bg-white"></div><div class="col-span-1 row-span-1 bg-white"></div>
                    </div>
                </div>
                <p class="font-bold text-2xl text-gray-800 mb-1">Rp <span x-text="formatRupiah(totalAmount)"></span></p>
                <p class="text-gray-500 text-sm text-center mb-6">Tunjukkan QR ini ke pembeli. Tunggu pembeli melakukan scan dan pembayaran selesai.</p>
                
                <div class="w-full flex space-x-3">
                    <button @click="cancelCheckout()" class="flex-1 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300">Batal</button>
                    <button @click="confirmPayment()" class="flex-1 py-2 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700">Sudah Dibayar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('posSystem', () => ({
            produkList: @json($produk),
            searchQuery: '',
            cart: [],
            paymentMethod: 'cash',
            isProcessing: false,
            showQrisModal: false,

            get filteredProduk() {
                if (this.searchQuery === '') {
                    return this.produkList;
                }
                return this.produkList.filter(p => p.nama_produk.toLowerCase().includes(this.searchQuery.toLowerCase()));
            },

            get totalAmount() {
                return this.cart.reduce((total, item) => total + (item.harga_jual * item.qty), 0);
            },

            addToCart(produk) {
                const existing = this.cart.find(i => i.id === produk.id);
                if (existing) {
                    if (existing.qty < produk.stok_produk) {
                        existing.qty++;
                    } else {
                        alert('Stok tidak mencukupi!');
                    }
                } else {
                    if (produk.stok_produk > 0) {
                        this.cart.push({ ...produk, qty: 1 });
                    } else {
                        alert('Stok kosong!');
                    }
                }
            },

            increaseQty(index) {
                const item = this.cart[index];
                const prod = this.produkList.find(p => p.id === item.id);
                if (item.qty < prod.stok_produk) {
                    item.qty++;
                }
            },

            decreaseQty(index) {
                if (this.cart[index].qty > 1) {
                    this.cart[index].qty--;
                } else {
                    this.cart.splice(index, 1);
                }
            },

            formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID').format(angka);
            },

            processCheckout() {
                if (this.cart.length === 0) return;

                if (this.paymentMethod === 'qris') {
                    this.showQrisModal = true;
                } else {
                    this.confirmPayment();
                }
            },

            cancelCheckout() {
                this.showQrisModal = false;
                this.isProcessing = false;
            },

            async confirmPayment() {
                this.isProcessing = true;
                
                try {
                    const response = await fetch('{{ route('pos.checkout') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            items: this.cart,
                            metode_pembayaran: this.paymentMethod,
                            total_pembayaran: this.totalAmount
                        })
                    });

                    const result = await response.json();

                    if (result.success) {
                        alert('Pembayaran Berhasil! Invoice: ' + result.invoice);
                        window.location.reload(); // Reload untuk merefresh data stok
                    } else {
                        alert('Gagal: ' + result.message);
                        this.isProcessing = false;
                        this.showQrisModal = false;
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan jaringan.');
                    this.isProcessing = false;
                    this.showQrisModal = false;
                }
            }
        }));
    });
</script>
@endsection
