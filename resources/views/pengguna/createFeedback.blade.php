@extends('layouts.app')

@section('title', 'Beri Feedback')

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
    
    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Star Rating Container */
    .shipping-rating-container {
        max-width: 400px;
        margin: 0 auto;
        padding: 1.5rem;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
    }

    .rating-title {
        color: #2c3e50;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .rating-subtitle {
        color: #6c757d;
        font-size: 0.9rem;
        margin: 0;
    }

    /* Star Rating Styles */
    .star-rating {
        padding: 0.5rem 0;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.25rem;
        flex-direction: row
    }

    .star-item {
        position: relative;
    }

    .star-input {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .star-label {
        cursor: pointer;
        padding: 8px;
        border-radius: 50%;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0;
    }

    .star-icon {
        font-size: 1.8rem;
        color: #ddd;
        transition: all 0.3s ease;
        filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.1));
    }

    /* Hover Effects */
    .star-label:hover {
        background: rgba(255, 193, 7, 0.1);
        transform: scale(1.1);
    }

    .star-label:hover .star-icon {
        color: #ffc107;
        transform: scale(1.05);
    }

    /* Progressive highlighting on hover */
    .star-rating:hover .star-icon {
        color: #ddd;
    }

    .star-label:hover .star-icon,
    .star-label:hover ~ .star-item .star-label .star-icon {
        color: #ffc107;
    }

    /* Selected state styling */
    .star-input:checked + .star-label .star-icon {
        color: #ffc107;
        text-shadow: 0 0 8px rgba(255, 193, 7, 0.3);
    }

    /* Fill stars up to selected rating */
    .star-input:checked ~ .star-item .star-input + .star-label .star-icon {
        color: #ffc107;
        text-shadow: 0 0 8px rgba(255, 193, 7, 0.3);
    }

    /* Focus styles for accessibility */
    .star-input:focus + .star-label {
        outline: 2px solid #0d6efd;
        outline-offset: 2px;
        border-radius: 4px;
    }

    /* Rating feedback styles */
    .rating-feedback {
        min-height: 60px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    #selected-rating .badge {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
    }

    /* Responsive design */
    @media (max-width: 576px) {
        .shipping-rating-container {
            padding: 1rem;
            margin: 0 0.5rem;
        }
        
        .star-icon {
            font-size: 1.5rem;
        }
        
        .star-label {
            padding: 6px;
        }
    }

    /* Animation for rating selection */
    @keyframes ratingSelected {
        0% { transform: scale(1); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }

    .star-input:checked + .star-label .star-icon {
        animation: ratingSelected 0.3s ease;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Informasi Pengiriman -->
            <div class="card shadow-sm border-0 rounded-3 mb-4 animate-fade-in">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="mb-0">Informasi Pengiriman</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Nomor Resi:</strong></p>
                            <p class="text-muted">{{ $pengiriman->nomor_resi }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Status:</strong></p>
                            <span class="badge bg-success">{{ $pengiriman->status }}</span>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Tujuan:</strong></p>
                            <p class="text-muted">{{ $pengiriman->alamatTujuan->alamat_lengkap ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Layanan:</strong></p>
                            <p class="text-muted">{{ $pengiriman->zonaPengiriman->layananPaket->nama_layanan ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Kurir:</strong></p>
                            <p class="text-muted">{{ $pengiriman->kurir->nama ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Tanggal Kirim:</strong></p>
                            <p class="text-muted">{{ $pengiriman->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Feedback -->
            <div class="card shadow-sm border-0 rounded-3 animate-fade-in">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h5 class="mb-0">Beri Penilaian</h5>
                </div>
                <div class="card-body">
                    <form id="feedback-form" class="needs-validation" novalidate>
                        @csrf
                        <input type="hidden" name="id_pengiriman" value="{{ $pengiriman->id_pengiriman }}">

                        <!-- Star Rating Section -->
                        <div class="shipping-rating-container mb-4">
                            <div class="rating-header text-center mb-3">
                                <h6 class="rating-title mb-1">Beri Rating Pengiriman</h6>
                                <p class="rating-subtitle text-muted small">Bagaimana pengalaman pengiriman Anda?</p>
                            </div>
                            
                            <div class="star-rating flex-row" role="radiogroup" aria-label="Rating pengiriman" style="flex-direction: row;">
                                @for ($i = 5; $i >= 1; $i--)
                                    <div class="star-item">
                                        <input type="radio" 
                                               class="star-input" 
                                               name="rating" 
                                               id="star-{{ $i }}" 
                                               value="{{ $i }}" 
                                               required>
                                        <label class="star-label" 
                                               for="star-{{ $i }}" 
                                               title="{{ $i }} dari 5 bintang">
                                            <i class="fas fa-star star-icon"></i>
                                        </label>
                                    </div>
                                @endfor
                            </div>
                            
                            <div class="rating-feedback text-center mt-3">
                                <small id="rating-description" class="text-muted">Pilih rating dari 1-5 bintang</small>
                                <div id="selected-rating" class="mt-2" style="display: none;">
                                    <span class="badge bg-primary">
                                        <i class="fas fa-star me-1"></i>
                                        <span id="rating-value">0</span>/5 - <span id="rating-text">Tidak ada rating</span>
                                    </span>
                                </div>
                                <div id="rating-error" class="text-danger mt-2" style="display: none;"></div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="komentar" class="form-label">Komentar (opsional)</label>
                            <textarea name="komentar" id="komentar" class="form-control" rows="4" 
                                placeholder="Bagaimana pengalaman Anda dengan layanan pengiriman ini?"></textarea>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('feedback.index') }}" class="btn btn-light me-md-2">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary" id="btn-submit">
                                <span class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                                <i class="fas fa-paper-plane me-2"></i>Kirim Feedback
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enable Bootstrap tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

    // Form elements
    const form = document.getElementById('feedback-form');
    const submitBtn = document.getElementById('btn-submit');
    const spinner = submitBtn.querySelector('.spinner-border');
    const ratingInputs = document.querySelectorAll('input[name="rating"]');
    const selectedRatingDiv = document.getElementById('selected-rating');
    const ratingValue = document.getElementById('rating-value');
    const ratingText = document.getElementById('rating-text');
    const ratingDescription = document.getElementById('rating-description');
    const ratingError = document.getElementById('rating-error');
    
    const ratingTexts = {
        1: 'Sangat Buruk',
        2: 'Buruk',
        3: 'Cukup',
        4: 'Baik',
        5: 'Sangat Baik'
    };
    
    // Handle rating selection
    ratingInputs.forEach(input => {
        input.addEventListener('change', function() {
            const rating = parseInt(this.value);
            
            // Update display
            ratingValue.textContent = rating;
            ratingText.textContent = ratingTexts[rating];
            selectedRatingDiv.style.display = 'block';
            ratingDescription.style.display = 'none';
            ratingError.style.display = 'none';
            
            // Update all stars visual state
            updateStarDisplay(rating);
            
            console.log('Rating dipilih:', rating);
        });
    });
    
    // Handle hover effects for better UX
    const starLabels = document.querySelectorAll('.star-label');
    const starRating = document.querySelector('.star-rating');
    
    starLabels.forEach((label, index) => {
        label.addEventListener('mouseenter', function() {
            const rating = 5 - index; // Karena kita render dari 5 ke 1
            highlightStars(rating);
        });
    });
    
    starRating.addEventListener('mouseleave', function() {
        const checkedInput = document.querySelector('input[name="rating"]:checked');
        if (checkedInput) {
            updateStarDisplay(parseInt(checkedInput.value));
        } else {
            clearStarHighlight();
        }
    });
    
    function highlightStars(rating) {
        starLabels.forEach((label, index) => {
            const star = label.querySelector('.star-icon');
            const starValue = 5 - index; // Karena kita render dari 5 ke 1
            if (starValue <= rating) {
                star.style.color = '#ffc107';
            } else {
                star.style.color = '#ddd';
            }
        });
    }
    
    function updateStarDisplay(rating) {
        starLabels.forEach((label, index) => {
            const star = label.querySelector('.star-icon');
            const starValue = 5 - index; // Karena kita render dari 5 ke 1
            if (starValue <= rating) {
                star.style.color = '#ffc107';
            } else {
                star.style.color = '#ddd';
            }
        });
    }
    
    function clearStarHighlight() {
        starLabels.forEach(label => {
            const star = label.querySelector('.star-icon');
            star.style.color = '#ddd';
        });
    }

    // Form submission
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        // Reset error state
        ratingError.textContent = '';
        ratingError.style.display = 'none';

        // Check if rating is selected
        const selectedRating = Array.from(ratingInputs).find(input => input.checked);
        if (!selectedRating) {
            ratingError.textContent = 'Silakan pilih rating terlebih dahulu';
            ratingError.style.display = 'block';
            return;
        }

        if (!form.checkValidity()) {
            e.stopPropagation();
            form.classList.add('was-validated');
            return;
        }

        Swal.fire({
            title: 'Kirim Feedback?',
            text: 'Pastikan rating dan komentar sudah sesuai',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kirim',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-light'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                submitBtn.disabled = true;
                spinner.classList.remove('d-none');

                axios.post('/feedback/store', new FormData(form))
                    .then(response => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Terima kasih!',
                            text: 'Feedback berhasil dikirim.',
                            timer: 2000,
                            showConfirmButton: false,
                            customClass: {
                                popup: 'animate__animated animate__fadeInDown'
                            }
                        }).then(() => {
                            window.location.href = '{{ route("feedback.index") }}';
                        });
                    })
                    .catch(err => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: err.response?.data?.message || 'Feedback gagal dikirim.',
                            customClass: {
                                popup: 'animate__animated animate__shakeX'
                            }
                        });
                    })
                    .finally(() => {
                        // Hide loading state
                        submitBtn.disabled = false;
                        spinner.classList.add('d-none');
                    });
            }
        });
    });
});
</script>
@endsection