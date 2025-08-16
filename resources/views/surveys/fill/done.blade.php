@extends('surveys.fill.layout')

@section('title', 'Survei Selesai - ' . $survey->nama)

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Success Card -->
    <div class="bg-white rounded-lg shadow-sm p-8 text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
            <i class="fas fa-check text-green-600 text-xl"></i>
        </div>
        
        <h2 class="text-2xl font-bold text-gray-900 mb-2">
            Terima Kasih!
        </h2>
        
        <p class="text-gray-600 mb-6">
            Anda telah berhasil menyelesaikan survei "<strong>{{ $survey->nama }}</strong>".
            Jawaban Anda telah tersimpan dengan aman.
        </p>

        @if($surveyUser && $surveyUser->tanggal_mengisi)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-center">
                    <i class="fas fa-clock text-blue-500 mr-2"></i>
                    <span class="text-sm text-blue-700">
                        Diselesaikan pada: 
                        <strong>{{ \Carbon\Carbon::parse($surveyUser->tanggal_mengisi)->format('d M Y H:i') }}</strong>
                    </span>
                </div>
            </div>
        @endif

        <!-- Survey Info -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Informasi Survei</h3>
            <div class="text-left space-y-2 text-sm text-gray-600">
                <div class="flex justify-between">
                    <span>Nama Survei:</span>
                    <span class="font-medium">{{ $survey->nama }}</span>
                </div>
                @if($survey->deskripsi)
                    <div class="flex justify-between">
                        <span>Deskripsi:</span>
                        <span class="font-medium text-right max-w-xs">{{ $survey->deskripsi }}</span>
                    </div>
                @endif
                <div class="flex justify-between">
                    <span>Periode:</span>
                    <span class="font-medium">
                        {{ \Carbon\Carbon::parse($survey->tanggal_mulai)->format('d M Y') }} - 
                        {{ \Carbon\Carbon::parse($survey->tanggal_selesai)->format('d M Y') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="space-y-3">
            @auth
                <a href="{{ route('user.monitoring.index') }}" 
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-chart-bar mr-2"></i>
                    Lihat Survei Saya
                </a>
            @endauth
            
            <div class="text-center">
                <button onclick="window.close()" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Tutup Halaman
                </button>
            </div>
        </div>
    </div>

    <!-- Additional Info -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <h4 class="text-sm font-medium text-blue-800 mb-1">Informasi Penting</h4>
                <div class="text-sm text-blue-700 space-y-1">
                    <p>• Data yang Anda berikan akan dijaga kerahasiaannya</p>
                    <p>• Hasil survei akan digunakan untuk keperluan akademis dan pengembangan program</p>
                    <p>• Jika ada pertanyaan, silakan hubungi admin sistem</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
