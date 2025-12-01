@extends('layouts.public')

@section('title', 'Scan Berhasil')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-500 via-teal-600 to-blue-500 flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- Success Animation Card -->
        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden animate-slideInUp">
            <!-- Header dengan Checkmark Animation -->
            <div class="bg-gradient-to-r from-green-500 to-teal-500 p-8 text-center">
                <div class="mb-4 flex justify-center">
                    <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center animate-bounce-slow">
                        <svg class="w-16 h-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Scan Berhasil! ✓</h1>
                <p class="text-green-100 text-lg">Transaksi Telah Dicatat</p>
            </div>

            <!-- Transaction Info -->
            <div class="p-8">
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 mb-6 border-2 border-blue-200">
                    <div class="text-center">
                        <p class="text-blue-600 text-xs font-bold uppercase tracking-wider mb-2">ID Transaksi</p>
                        <p class="text-2xl font-mono font-bold text-gray-800">{{ $latestTransaction->transaction_id }}</p>
                        <p class="text-xs text-gray-500 mt-2">{{ $latestTransaction->scan_time->format('d/m/Y H:i:s') }}</p>
                    </div>
                </div>

                <!-- Driver Info -->
                <div class="text-center mb-6">
                    <div class="w-20 h-20 mx-auto bg-gradient-to-br from-primary-500 to-purple-600 rounded-full flex items-center justify-center mb-3 shadow-lg">
                        <span class="text-white font-bold text-2xl">{{ strtoupper(substr($driver->name, 0, 2)) }}</span>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">{{ $driver->name }}</h2>
                    <p class="text-gray-500 font-mono text-sm">{{ $driver->driver_id_card }}</p>
                </div>

                <!-- Status Steps -->
                <div class="space-y-3 mb-6">
                    <div class="flex items-start space-x-3 bg-green-50 border-l-4 border-green-500 p-3 rounded">
                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-green-800">1. Scan Barcode</p>
                            <p class="text-xs text-green-600">Berhasil dicatat oleh sistem</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3 bg-yellow-50 border-l-4 border-yellow-500 p-3 rounded">
                        <div class="w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 animate-pulse">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-yellow-800">2. Menunggu Konfirmasi Admin</p>
                            <p class="text-xs text-yellow-600">Admin akan verifikasi dan input data pasien</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-3 bg-gray-50 border-l-4 border-gray-300 p-3 rounded">
                        <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-600">3. Poin Ditambahkan</p>
                            <p class="text-xs text-gray-500">+1 poin setelah admin konfirmasi</p>
                        </div>
                    </div>
                </div>

                <!-- Current Points Display -->
                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl p-5 border-2 border-yellow-200">
                    <div class="text-center">
                        <p class="text-gray-600 text-xs font-medium uppercase tracking-wide mb-2">Total Poin Saat Ini</p>
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-7 h-7 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-yellow-600 to-orange-600">
                                {{ number_format($driver->total_points) }}
                            </span>
                        </div>
                        <p class="text-gray-500 text-xs mt-2">Total transaksi terkonfirmasi: {{ $driver->total_confirmed_transactions }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Note -->
        <div class="text-center mt-6 space-y-2">
            <p class="text-white text-base drop-shadow-lg font-semibold">
                Terima kasih atas dedikasi Anda! 🚑
            </p>
            <p class="text-green-100 text-sm drop-shadow">
                Silakan antarkan pasien ke ruangan yang dituju
            </p>
        </div>
    </div>
</div>

<style>
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(50px) scale(0.9);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes bounce-slow {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-15px);
    }
}

.animate-slideInUp {
    animation: slideInUp 0.6s ease-out;
}

.animate-bounce-slow {
    animation: bounce-slow 2s ease-in-out infinite;
}
</style>
@endsection
