@extends('surveys.fill.layout')

@section('title', 'Survei Tidak Aktif - ' . $survey->nama)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-8 text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
            <i class="fas fa-clock text-yellow-600 text-xl"></i>
        </div>
        
        <h2 class="text-2xl font-bold text-gray-900 mb-2">
            Survei Tidak Tersedia
        </h2>
        
        <p class="text-gray-600 mb-6">
            Survei "<strong>{{ $survey->nama }}</strong>" saat ini tidak dapat diakses.
        </p>

        <!-- Survey Status -->
        <div class="bg-gray-50 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Survei</h3>
            <div class="space-y-3 text-sm text-gray-600">
                <div class="flex justify-between items-center">
                    <span>Nama Survei:</span>
                    <span class="font-medium">{{ $survey->nama }}</span>
                </div>
                
                @if($survey->deskripsi)
                    <div class="flex justify-between items-center">
                        <span>Deskripsi:</span>
                        <span class="font-medium text-right max-w-xs">{{ $survey->deskripsi }}</span>
                    </div>
                @endif
                
                <div class="flex justify-between items-center">
                    <span>Tanggal Mulai:</span>
                    <span class="font-medium">{{ \Carbon\Carbon::parse($survey->tanggal_mulai)->format('d M Y H:i') }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span>Tanggal Selesai:</span>
                    <span class="font-medium">{{ \Carbon\Carbon::parse($survey->tanggal_selesai)->format('d M Y H:i') }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span>Status:</span>
                    @php
                        $now = now();
                        $startDate = \Carbon\Carbon::parse($survey->tanggal_mulai);
                        $endDate = \Carbon\Carbon::parse($survey->tanggal_selesai);
                    @endphp
                    
                    @if($now < $startDate)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-1"></i>
                            Belum Dimulai
                        </span>
                    @elseif($now > $endDate)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <i class="fas fa-times-circle mr-1"></i>
                            Sudah Berakhir
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i class="fas fa-play-circle mr-1"></i>
                            Aktif
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Message based on status -->
        <div class="mb-6">
            @if($now < $startDate)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-yellow-800">Survei Belum Dimulai</h4>
                            <p class="text-sm text-yellow-700 mt-1">
                                Survei ini akan dibuka pada <strong>{{ $startDate->format('d M Y H:i') }}</strong>.
                                Silakan kembali lagi pada waktu tersebut.
                            </p>
                        </div>
                    </div>
                </div>
            @elseif($now > $endDate)
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-times-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-red-800">Survei Sudah Berakhir</h4>
                            <p class="text-sm text-red-700 mt-1">
                                Survei ini telah berakhir pada <strong>{{ $endDate->format('d M Y H:i') }}</strong>.
                                Anda tidak dapat lagi mengisi survei ini.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
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
                    <i class="fas fa-arrow-left mr-2"></i>
                    Tutup Halaman
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
