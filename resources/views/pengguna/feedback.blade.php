@extends('layouts.app')

@section('title', 'Feedback Pengiriman')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<style>
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    .nav-tabs .nav-link {
        transition: all 0.3s ease;
        border: none;
        color: var(--bs-gray-600);
        padding: 1rem 1.5rem;
    }
    .nav-tabs .nav-link:hover {
        color: var(--bs-primary);
        background: rgba(13, 110, 253, 0.1);
        border: none;
    }
    .nav-tabs .nav-link.active {
        color: var(--bs-primary);
        background: rgba(13, 110, 253, 0.1);
        border: none;
        position: relative;
    }
    .nav-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background: var(--bs-primary);
        animation: slideIn 0.3s ease-out forwards;
    }
    @keyframes slideIn {
        from { transform: scaleX(0); }
        to { transform: scaleX(1); }
    }
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .rating-stars .fa-star {
    color: #ffc107; /* bintang aktif */
}

.rating-stars .fa-star.empty {
    color: #e4e4e4; /* bintang kosong */
}
    
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 rounded-3 animate-fade-in">
        <div class="card-header bg-white border-bottom-0 py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4 class="mb-0">Feedback Pengiriman</h4>
                    <p class="text-muted mb-0 mt-1">Berikan penilaian untuk pengiriman yang telah selesai</p>
                </div>
                <div class="col-md-6">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="p-3 rounded-3 bg-primary bg-opacity-10">
                                <h3 class="text-primary mb-0">{{ $pengirimanTanpaFeedback->count() }}</h3>
                                <small class="text-muted">Belum Dinilai</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-3 bg-success bg-opacity-10">
                                <h3 class="text-success mb-0">{{ $pengirimanDenganFeedback->count() }}</h3>
                                <small class="text-muted">Sudah Dinilai</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="feedbackTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="unrated-tab" data-bs-toggle="tab" data-bs-target="#unrated" type="button" role="tab" aria-controls="unrated" aria-selected="true">
                        <i class="fas fa-clock me-2"></i>Belum Dinilai
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="rated-tab" data-bs-toggle="tab" data-bs-target="#rated" type="button" role="tab" aria-controls="rated" aria-selected="false">
                        <i class="fas fa-check-circle me-2"></i>Sudah Dinilai
                    </button>
                </li>
            </ul>

            <div class="tab-content pt-4" id="feedbackTabsContent">
                <div class="tab-pane fade show active animate-fade-in" id="unrated" role="tabpanel" aria-labelledby="unrated-tab">
                    @if($pengirimanTanpaFeedback->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada pengiriman yang perlu dinilai</h5>
                        </div>
                    @else
                        <div class="row g-4">
                            @foreach($pengirimanTanpaFeedback as $pengiriman)
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="card-title mb-0">
                                                    <span class="badge bg-primary me-2">{{  $pengiriman->nomor_resi }}</span>
                                                </h6>
                                                <span class="badge bg-info">
                                                    <i class="fas fa-truck me-1"></i>{{ $pengiriman->status }}
                                                </span>
                                            </div>
                                            <div class="mb-3">
                                                <small class="text-muted d-block mb-1">
                                                    <i class="fas fa-map-marker-alt me-2"></i>{{ $pengiriman->alamatTujuan->alamat_lengkap ?? 'N/A' }}
                                                </small>
                                                <small class="text-muted d-block mb-1">
                                                    <i class="fas fa-box me-2"></i>{{ $pengiriman->layananPaket->nama_layanan ?? 'N/A' }}
                                                </small>
                                                <small class="text-muted d-block mb-1">
                                                    <i class="fas fa-shipping-fast me-2"></i>{{ $pengiriman->kurir->nama ?? 'N/A' }}
                                                </small>
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-calendar-alt me-2"></i>{{ $pengiriman->created_at->format('d/m/Y H:i')}}
                                                </small>
                                            </div>
                                            <div class="d-grid gap-2">
                                                <a href="{{ route('pengguna.createFeedback', $pengiriman->id_pengiriman) }}" class="btn btn-primary">
                                                    <i class="fas fa-star me-2"></i>Beri Penilaian
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="tab-pane fade animate-fade-in" id="rated" role="tabpanel" aria-labelledby="rated-tab">
                    @if($pengirimanDenganFeedback->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-star fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada pengiriman yang dinilai</h5>
                        </div>
                    @else
                        <div class="row g-4">
                            @foreach($pengirimanDenganFeedback as $pengiriman)
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="card-title mb-0">
                                                    <span class="badge bg-primary me-2">{{ $pengiriman->nomor_resi }}</span>
                                                </h6>
                                                <div class="rating-stars">
                                                    @php
                                                         $rating = optional($pengiriman->feedback)->rating ?? 0;
                                                    @endphp
                                                    @for($i = 1; $i <= $rating; $i++)
                                                        <i class="fas fa-star {{ $i <= $rating ? '' : 'empty' }}"></i>
                                                        
                                                    @endfor
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <small class="text-muted d-block mb-1">
                                                    <i class="fas fa-map-marker-alt me-2"></i>{{ $pengiriman->alamatTujuan->alamat_lengkap ?? 'N/A' }}
                                                </small>
                                                <small class="text-muted d-block mb-1">
                                                    <i class="fas fa-box me-2"></i>{{ $pengiriman->layananPaket->nama_layanan ?? 'N/A' }}
                                                </small>
                                                <small class="text-muted d-block mb-1">
                                                    <i class="fas fa-shipping-fast me-2"></i>{{ $pengiriman->kurir->nama ?? 'N/A' }}
                                                </small>
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-calendar-alt me-2"></i>{{ $pengiriman->created_at->format('d/m/Y H:i')}}
                                                </small>
                                                @if($pengiriman->feedback->komentar)
                                                    <div class="mt-3">
                                                        <small class="text-muted d-block">
                                                            <i class="fas fa-comment me-2"></i>{{ $pengiriman->feedback->komentar }}
                                                        </small>
                                                    </div>
                                                @endif
                                            </div>
                                           
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Add animation when switching tabs
        var tabEl = document.querySelector('button[data-bs-toggle="tab"]');
        tabEl.addEventListener('shown.bs.tab', function (event) {
            var targetPane = document.querySelector(event.target.getAttribute('data-bs-target'));
            targetPane.classList.add('animate-fade-in');
        });
    });
</script>
@endsection

<!-- Alert Messages -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed bottom-0 end-0 m-3" role="alert" style="z-index: 1050;">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show position-fixed bottom-0 end-0 m-3" role="alert" style="z-index: 1050;">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

       

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