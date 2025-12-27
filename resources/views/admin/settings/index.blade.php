@extends('admin.layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <h4 class="fw-bold mb-4">Ulgam Sazlamalary</h4>

            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-hourglass-split me-2"></i>Loader (Garaşylýan sahypa) Sazlamalary</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Yüklenýän wagtky tekst</label>
                                <input type="text" name="loader_text" class="form-control"
                                    value="{{ $settings['loader_text'] ?? 'Market ýüklenýär...' }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Arka reňki</label>
                                <input type="color" name="loader_bg_color" class="form-control form-control-color w-100"
                                    value="{{ $settings['loader_bg_color'] ?? '#ffffff' }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Loader Logosy</label>
                                <input type="file" name="loader_logo" class="form-control mb-2">
                            </div>

                            <div class="col-md-6 d-flex align-items-end">
                                @if(isset($settings['loader_logo']))
                                <div class="p-2 border rounded bg-light">
                                    <img src="{{ asset('storage/' . $settings['loader_logo']) }}" style="height: 60px; object-fit: contain;">
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-share me-2"></i>Habarlaşmak we Sosial Media (Footer)</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold"><i class="bi bi-telephone me-1"></i>Telefon belgi</label>
                                <input type="text" name="phone" class="form-control" value="{{ $settings['phone'] ?? '' }}" placeholder="+993 65 ...">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold"><i class="bi bi-envelope me-1"></i>Email adresi</label>
                                <input type="email" name="email" class="form-control" value="{{ $settings['email'] ?? '' }}" placeholder="info@market.com">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold"><i class="bi bi-geo-alt me-1"></i>Salgy (Address)</label>
                                <input type="text" name="address" class="form-control" value="{{ $settings['address'] ?? '' }}" placeholder="Aşgabat, Türkmenistan">
                            </div>

                            <hr class="my-3">

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-danger"><i class="bi bi-instagram me-1"></i>Instagram Link</label>
                                <input type="text" name="instagram" class="form-control" value="{{ $settings['instagram'] ?? '' }}" placeholder="https://instagram.com/username">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-primary"><i class="bi bi-facebook me-1"></i>Facebook Link</label>
                                <input type="text" name="facebook" class="form-control" value="{{ $settings['facebook'] ?? '' }}" placeholder="https://facebook.com/page">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-info"><i class="bi bi-telegram me-1"></i>Telegram Link</label>
                                <input type="text" name="telegram" class="form-control" value="{{ $settings['telegram'] ?? '' }}" placeholder="https://t.me/username">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark"><i class="bi bi-tiktok me-1"></i>TikTok Link</label>
                                <input type="text" name="tiktok" class="form-control" value="{{ $settings['tiktok'] ?? '' }}" placeholder="https://tiktok.com/@username">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary px-5 fw-bold text-uppercase">
                        <i class="bi bi-save me-2"></i>Sazlamalary ýatda sakla
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection