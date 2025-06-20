@extends('layouts.app')

@section('title', 'Feedback Pengiriman')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Feedback Pengiriman</h1>
                <p class="text-gray-600 mt-1">Berikan penilaian untuk pengiriman yang telah selesai</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $pengirimanTanpaFeedback->count() }}</div>
                    <div class="text-sm text-gray-500">Belum Dinilai</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $pengirimanDenganFeedback->count() }}</div>
                    <div class="text-sm text-gray-500">Sudah Dinilai</div>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button id="tab-pending" class="tab-button active py-2 px-1 border-b-2 font-medium text-sm" data-tab="pending">
                    Belum Dinilai
                    @if($pengirimanTanpaFeedback->count() > 0)
                        <span class="ml-2 bg-red-100 text-red-600 py-1 px-2 rounded-full text-xs">{{ $pengirimanTanpaFeedback->count() }}</span>
                    @endif
                </button>
                <button id="tab-completed" class="tab-button py-2 px-1 border-b-2 font-medium text-sm" data-tab="completed">
                    Sudah Dinilai
                    @if($pengirimanDenganFeedback->count() > 0)
                        <span class="ml-2 bg-green-100 text-green-600 py-1 px-2 rounded-full text-xs">{{ $pengirimanDenganFeedback->count() }}</span>
                    @endif
                </button>
            </nav>
        </div>

        <!-- Tab Content: Pengiriman Belum Dinilai -->
        <div id="content-pending" class="tab-content">
            @if($pengirimanTanpaFeedback->count() > 0)
                <div class="space-y-4">
                    @foreach($pengirimanTanpaFeedback as $pengiriman)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="font-semibold text-gray-800">Resi: {{ $pengiriman->nomor_resi }}</h3>
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                            {{ $pengiriman->status }}
                                        </span>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                                        <div>
                                            <p><strong>Tujuan:</strong> {{ $pengiriman->alamatTujuan->alamat_lengkap ?? 'N/A' }}</p>
                                            <p><strong>Layanan:</strong> {{ $pengiriman->layananPaket->nama_layanan ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <p><strong>Kurir:</strong> {{ $pengiriman->kurir->nama ?? 'N/A' }}</p>
                                            <p><strong>Tanggal Kirim:</strong> {{ $pengiriman->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </div>
                                    
                                    @if($pengiriman->catatan_opsional)
                                        <p class="text-sm text-gray-500 mt-2">
                                            <strong>Catatan:</strong> {{ $pengiriman->catatan_opsional }}
                                        </p>
                                    @endif
                                </div>
                                
                                <div class="ml-4">
                                    <form action="{{ route('feedback.create', $pengiriman->id_pengiriman) }}" method="get">
                                        <button type="submit" 
                                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center">
                                            <i class="fas fa-star mr-2"></i>Beri Penilaian
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-gray-400 text-6xl mb-4">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Pengiriman yang Perlu Dinilai</h3>
                    <p class="text-gray-500">Semua pengiriman yang selesai sudah diberi penilaian.</p>
                </div>
            @endif
        </div>

        <!-- Tab Content: Pengiriman Sudah Dinilai -->
        <div id="content-completed" class="tab-content hidden">
            @if($pengirimanDenganFeedback->count() > 0)
                <div class="space-y-4">
                    @foreach($pengirimanDenganFeedback as $pengiriman)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="font-semibold text-gray-800">Resi: {{ $pengiriman->nomor_resi }}</h3>
                                        <div class="flex items-center space-x-2">
                                            <!-- Rating Display -->
                                            <div class="flex items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $pengiriman->feedback->rating)
                                                        <i class="fas fa-star text-yellow-400"></i>
                                                    @else
                                                        <i class="far fa-star text-gray-300"></i>
                                                    @endif
                                                @endfor
                                                <span class="ml-2 text-sm font-medium text-gray-600">
                                                    ({{ $pengiriman->feedback->rating }}/5)
                                                </span>
                                            </div>
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                                {{ $pengiriman->status }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600 mb-3">
                                        <div>
                                            <p><strong>Tujuan:</strong> {{ $pengiriman->alamatTujuan->alamat_lengkap ?? 'N/A' }}</p>
                                            <p><strong>Layanan:</strong> {{ $pengiriman->layananPaket->nama_layanan ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <p><strong>Kurir:</strong> {{ $pengiriman->kurir->nama ?? 'N/A' }}</p>
                                            <p><strong>Tanggal Kirim:</strong> {{ $pengiriman->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </div>

                                    @if($pengiriman->feedback->komentar)
                                        <div class="bg-gray-50 rounded-lg p-3 mt-3">
                                            <p class="text-sm text-gray-700">
                                                <strong>Komentar Anda:</strong> {{ $pengiriman->feedback->komentar }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                Diberikan pada: {{ $pengiriman->feedback->created_at->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="ml-4 flex flex-col space-y-2">
                                    <a href="{{ route('feedback.show', $pengiriman->id_pengiriman) }}" 
                                       class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors text-center">
                                        <i class="fas fa-eye mr-2"></i>Detail
                                    </a>
                                    <a href="{{ route('feedback.edit', $pengiriman->id_pengiriman) }}" 
                                       class="bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded-lg transition-colors text-center">
                                        <i class="fas fa-edit mr-2"></i>Edit
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-gray-400 text-6xl mb-4">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Penilaian</h3>
                    <p class="text-gray-500">Anda belum memberikan penilaian untuk pengiriman manapun.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    .tab-button {
        @apply text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300;
    }
    
    .tab-button.active {
        @apply text-blue-600 border-blue-500;
    }
    
    .tab-content {
        animation: fadeIn 0.3s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all buttons
            tabButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Hide all tab contents
            tabContents.forEach(content => content.classList.add('hidden'));
            
            // Show target tab content
            document.getElementById(`content-${targetTab}`).classList.remove('hidden');
        });
    });
});
</script>
@endpush
@endsection