@extends('layouts.app')

@section('title', 'Edit Detail Tutorial')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-dark">Edit Detail Tutorial</h3>
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

                <form action="{{ route('detail-tutorial.update', $detail->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Teks Penjelasan</label>
                        <textarea name="text" class="form-control" rows="4">{{ old('text', $detail->text) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Gambar Saat Ini</label>
                        @if($detail->gambar)
                            <div id="imagePreviewContainer" class="position-relative d-inline-block mb-3">
                                <img src="{{ $detail->gambar }}" alt="Current Image" style="max-height: 150px;" class="img-thumbnail">
                                <button type="button" id="btnHapusGambar" class="btn btn-danger btn-sm position-absolute top-0 start-100 translate-middle rounded-circle shadow" title="Hapus Gambar">
                                    &times;
                                </button>
                            </div>
                        @endif
                        <p id="noImageText" class="text-muted small mt-2" style="display: {{ $detail->gambar ? 'none' : 'block' }}">Belum ada gambar</p>
                        <!-- Input tersembunyi penanda hapus gambar -->
                        <input type="hidden" name="hapus_gambar" id="hapusGambarInput" value="0">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Ubah/Tambah Gambar (Upload)</label>
                        <input type="file" name="gambar_file" class="form-control" accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Ubah/Tambah URL Gambar (Opsional)</label>
                        <input type="url" name="gambar_url" class="form-control" value="{{ old('gambar_url', ($detail->gambar && !str_contains($detail->gambar, '/storage/') && filter_var($detail->gambar, FILTER_VALIDATE_URL) ? $detail->gambar : '')) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Potongan Kode (Code Snippet)</label>
                        <textarea name="code" class="form-control font-monospace bg-light" rows="5">{{ old('code', $detail->code) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">URL Referensi Tambahan</label>
                        <input type="url" name="url" class="form-control" value="{{ old('url', $detail->url) }}">
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Urutan Tampil (Order)</label>
                            <input type="number" name="order" class="form-control" value="{{ old('order', $detail->order) }}" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="show" {{ old('status', $detail->status) == 'show' ? 'selected' : '' }}>Show (Tampil Publik)</option>
                                <option value="hide" {{ old('status', $detail->status) == 'hide' ? 'selected' : '' }}>Hide (Sembunyikan)</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('detail-tutorial.byMaster', $master->id) }}" class="btn btn-secondary w-50 py-2 fw-bold">Batal Edit</a>
                        <button type="submit" class="btn btn-warning text-white w-50 py-2 fw-bold">Update Detail</button>
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
        const btnHapusGambar = document.getElementById('btnHapusGambar');
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');
        const noImageText = document.getElementById('noImageText');

        if (btnHapusGambar) {
            btnHapusGambar.addEventListener('click', function() {
                Swal.fire({
                    title: 'Hapus gambar secara permanen?',
                    text: 'Tindakan ini tidak bisa dibatalkan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus Segera',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Set value input tersembunyi menjadi 1
                        document.getElementById('hapusGambarInput').value = '1';
                        // Sembunyikan gambar dan tombol
                        imagePreviewContainer.style.display = 'none';
                        // Tampilkan teks "Belum ada gambar"
                        noImageText.style.display = 'block';
                        
                        // KOSONGKAN input URL gambar agar tidak terkirim kembali ke server
                        const urlInput = document.querySelector('input[name="gambar_url"]');
                        if (urlInput) urlInput.value = '';
                        
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Gambar akan dihapus saat menekan Update Detail.',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                });
            });
        }

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
