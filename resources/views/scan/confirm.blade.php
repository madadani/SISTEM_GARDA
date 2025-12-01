@extends('layouts.app')

@section('title', 'Konfirmasi Scan')
@section('page-title', 'Konfirmasi Scan')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('scan.index') }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Transaction Info Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-primary-600 to-purple-600 px-6 py-4">
            <h2 class="text-xl font-bold text-white">Konfirmasi Scan Driver</h2>
            <p class="text-primary-100 text-sm mt-1">Verifikasi dan input data pasien</p>
        </div>

        <div class="p-6 space-y-6">
            <!-- Transaction Details -->
            <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-medium">ID Transaksi</p>
                        <p class="text-lg font-mono font-bold text-gray-900">{{ $transaction->transaction_id }}</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Pending
                    </span>
                </div>

                <div class="border-t border-gray-200 pt-3">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-lg mr-4">
                            {{ strtoupper(substr($transaction->driver->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Driver</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $transaction->driver->name }}</p>
                            <p class="text-xs text-gray-500">{{ $transaction->driver->driver_id_card }} • {{ $transaction->driver->phone_number }}</p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-3 grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-medium">Waktu Scan</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $transaction->scan_time->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-medium">Poin akan diberikan</p>
                        <p class="text-sm font-semibold text-yellow-600">+{{ $transaction->points_awarded }} poin</p>
                    </div>
                </div>
            </div>

            <!-- Patient Data Form -->
            <form action="{{ route('scan.process-confirm', $transaction->id) }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label for="patient_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Pasien
                        <span class="text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <input type="text" id="patient_name" name="patient_name" value="{{ old('patient_name') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('patient_name') border-red-500 @enderror" placeholder="Masukkan nama pasien">
                    @error('patient_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="patient_condition" class="block text-sm font-medium text-gray-700 mb-2">
                        Keluhan/Diagnosis Awal
                        <span class="text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <textarea id="patient_condition" name="patient_condition" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('patient_condition') border-red-500 @enderror" placeholder="Contoh: Demam tinggi, sesak napas, dll.">{{ old('patient_condition') }}</textarea>
                    @error('patient_condition')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="destination" class="block text-sm font-medium text-gray-700 mb-2">
                        Tujuan <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative flex items-center justify-center p-4 border-2 rounded-lg cursor-pointer transition-all duration-200 has-[:checked]:border-primary-600 has-[:checked]:bg-primary-50 has-[:checked]:shadow-lg border-gray-300 hover:border-primary-400 {{ old('destination') === 'IGD' ? 'border-primary-600 bg-primary-50 shadow-lg' : '' }}">
                            <input type="radio" name="destination" value="IGD" {{ old('destination') === 'IGD' ? 'checked' : '' }} required class="peer absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="text-center pointer-events-none relative z-0">
                                <svg class="w-10 h-10 mx-auto mb-2 transition-colors peer-checked:text-primary-600 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <span class="font-bold text-lg peer-checked:text-primary-700 text-gray-700">IGD</span>
                                <p class="text-xs peer-checked:text-primary-600 text-gray-500 mt-1">Instalasi Gawat Darurat</p>
                            </div>
                            <div class="absolute top-2 right-2 w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-primary-600 peer-checked:bg-primary-600 flex items-center justify-center transition-all">
                                <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </label>
                        
                        <label class="relative flex items-center justify-center p-4 border-2 rounded-lg cursor-pointer transition-all duration-200 has-[:checked]:border-primary-600 has-[:checked]:bg-primary-50 has-[:checked]:shadow-lg border-gray-300 hover:border-primary-400 {{ old('destination') === 'Ponek' ? 'border-primary-600 bg-primary-50 shadow-lg' : '' }}">
                            <input type="radio" name="destination" value="Ponek" {{ old('destination') === 'Ponek' ? 'checked' : '' }} required class="peer absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="text-center pointer-events-none relative z-0">
                                <svg class="w-10 h-10 mx-auto mb-2 transition-colors peer-checked:text-primary-600 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span class="font-bold text-lg peer-checked:text-primary-700 text-gray-700">Ponek</span>
                                <p class="text-xs peer-checked:text-primary-600 text-gray-500 mt-1">Pelayanan Obstetri Neonatal</p>
                            </div>
                            <div class="absolute top-2 right-2 w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-primary-600 peer-checked:bg-primary-600 flex items-center justify-center transition-all">
                                <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </label>
                    </div>
                    @error('destination')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white font-semibold rounded-lg shadow-lg hover:from-green-700 hover:to-teal-700 transition-all duration-200 hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Konfirmasi & Berikan Poin
                    </button>
                </div>
            </form>

            <!-- Reject Button -->
            <form action="{{ route('scan.reject', $transaction->id) }}" method="POST" onsubmit="return confirmReject(event, this)">
                @csrf
                <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-2.5 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Tolak Scan
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function confirmReject(event, form) {
    event.preventDefault();
    
    Swal.fire({
        title: 'Tolak Scan?',
        text: "Scan ini akan ditolak dan driver tidak mendapat poin",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Tolak!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
    
    return false;
}
</script>
@endsection
