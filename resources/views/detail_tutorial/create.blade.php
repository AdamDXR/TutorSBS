@extends('layouts.app')

@section('title', 'Tambah Detail Tutorial')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-dark">Tambah Detail Tutorial</h3>
            <a href="{{ route('detail-tutorial.byMaster', $master->id) }}" class="btn btn-outline-secondary">Kembali</a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('detail-tutorial.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="master_tutorial_id" value="{{ $master->id }}">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Teks Penjelasan</label>
                        <textarea name="text" class="form-control" rows="4">{{ old('text') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Gambar (Upload dari Perangkat)</label>
                        <input type="file" name="gambar_file" class="form-control" accept="image/*">
                        <div class="form-text">Maksimal 2MB (opsional, akan memprioritaskan upload file daripada URL).</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">URL Gambar (Opsional)</label>
                        <input type="url" name="gambar_url" class="form-control" value="{{ old('gambar_url') }}" placeholder="https://example.com/image.jpg">
                        <div class="form-text">Jika tidak upload file, Anda bisa menggunakan URL eksternal (contoh: imgur).</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Potongan Kode (Code Snippet)</label>
                        <textarea name="code" class="form-control font-monospace bg-light" rows="5">{{ old('code') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">URL Referensi Tambahan</label>
                        <input type="url" name="url" class="form-control" value="{{ old('url') }}" placeholder="https://...">
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Urutan Tampil (Order)</label>
                            <input type="number" name="order" class="form-control" value="{{ old('order', 0) }}" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="show" {{ old('status') == 'show' ? 'selected' : '' }}>Show (Tampil Publik)</option>
                                <option value="hide" {{ old('status') == 'hide' ? 'selected' : '' }}>Hide (Sembunyikan)</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('detail-tutorial.byMaster', $master->id) }}" class="btn btn-secondary w-50 py-2 fw-bold">Batal Tambah</a>
                        <button type="submit" class="btn btn-primary w-50 py-2 fw-bold">Simpan Detail</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Disable input URL jika user memilih file gambar dari perangkat
        const gambarFileInput = document.querySelector('input[name="gambar_file"]');
        const gambarUrlInput = document.querySelector('input[name="gambar_url"]');

        if (gambarFileInput && gambarUrlInput) {
            gambarFileInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    gambarUrlInput.value = ''; // Kosongkan URL
                    gambarUrlInput.disabled = true; // Nonaktifkan input URL
                } else {
                    gambarUrlInput.disabled = false; // Aktifkan kembali jika batal pilih file
                }
            });
        }
    });
</script>
@endpush
