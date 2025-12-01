@extends('layouts.public')

@section('title', 'Total Poin Driver')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-primary-500 via-purple-600 to-pink-500 flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- Success Animation Card -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
            <!-- Header dengan Checkmark Animation -->
            <div class="bg-gradient-to-r from-primary-500 to-purple-600 p-8 text-center">
                <div class="mb-4 flex justify-center">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <h1 class="text-2xl font-bold text-white mb-2">Total Poin Driver</h1>
                <p class="text-primary-100">Sistem Reward GARDA</p>
            </div>

            <!-- Driver Info -->
            <div class="p-8">
                <div class="text-center mb-6">
                    <div class="w-24 h-24 mx-auto bg-gradient-to-br from-primary-500 to-purple-600 rounded-full flex items-center justify-center mb-4 shadow-lg">
                        <span class="text-white font-bold text-3xl">{{ strtoupper(substr($driver->name, 0, 2)) }}</span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-1">{{ $driver->name }}</h2>
                    <p class="text-gray-500 font-mono text-sm">{{ $driver->driver_id_card }}</p>
                    <p class="text-gray-600 text-sm mt-1">{{ $driver->phone_number }}</p>
                </div>

                <!-- Points Display -->
                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl p-6 mb-6 border-2 border-yellow-200">
                    <div class="text-center">
                        <p class="text-gray-600 text-sm font-medium uppercase tracking-wide mb-2">Total Poin Anda</p>
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-yellow-600 to-orange-600">
                                {{ number_format($driver->total_points) }}
                            </span>
                        </div>
                        <p class="text-gray-500 text-xs mt-2">Poin dapat ditukar dengan hadiah menarik</p>
                    </div>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-gray-500 text-xs font-medium uppercase mb-1">Total Transaksi</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $driver->total_confirmed_transactions }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <p class="text-gray-500 text-xs font-medium uppercase mb-1">Poin per Trip</p>
                        <p class="text-2xl font-bold text-yellow-600">+1</p>
                    </div>
                </div>

                <!-- Status Info -->
                @if($latestTransaction)
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-4">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm text-blue-800 font-medium">Transaksi Terakhir:</p>
                            <p class="text-xs text-blue-600 mt-1">{{ $latestTransaction->scan_time->diffForHumans() }}</p>
                            <p class="text-xs text-blue-600 mt-1">
                                Status: 
                                @if($latestTransaction->status === 'PENDING')
                                <span class="font-semibold">⏳ Menunggu Konfirmasi</span>
                                @elseif($latestTransaction->status === 'CONFIRMED')
                                <span class="font-semibold text-green-600">✅ Dikonfirmasi</span>
                                @else
                                <span class="font-semibold text-red-600">❌ Ditolak</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-4">
                    <div class="text-center">
                        <p class="text-sm text-gray-600">Belum ada transaksi</p>
                        <p class="text-xs text-gray-500 mt-1">Scan barcode saat bawa pasien untuk mencatat transaksi</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Footer Note -->
        <div class="text-center mt-6">
            <p class="text-white text-sm drop-shadow-lg">
                Scan QR ini hanya untuk melihat poin 💰
            </p>
            <p class="text-white text-xs drop-shadow mt-1 opacity-75">
                Untuk mencatat transaksi, scan barcode saat bawa pasien
            </p>
        </div>
    </div>
</div>

<style>
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.bg-white {
    animation: slideInUp 0.5s ease-out;
}
</style>
@endsection
