@extends('layouts.app')

@section('title', 'Tambah Master Tutorial')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-dark">Tambah Master Tutorial</h3>
            <a href="{{ route('master-tutorial.index') }}" class="btn btn-outline-secondary">Kembali</a>
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

                <form action="{{ route('master-tutorial.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Tutorial</label>
                        <input type="text" name="judul" class="form-control" value="{{ old('judul') }}" required placeholder="Contoh: Belajar Laravel 11">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Mata Kuliah</label>
                        @if(isset($makul['data']) && is_array($makul['data']))
                            <select name="kode_makul" class="form-select" required>
                                <option value="">-- Pilih Mata Kuliah --</option>
                                @foreach($makul['data'] as $m)
                                    <option value="{{ $m['kdmk'] }}" {{ old('kode_makul') == $m['kdmk'] ? 'selected' : '' }}>
                                        {{ $m['kdmk'] }} - {{ $m['nama'] }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            {{-- Fix 5: Tampilkan spinner jika data makul belum tersedia atau error saat fetch --}}
                            <div class="d-flex align-items-center text-muted p-2 border rounded bg-light">
                                <div class="spinner-border spinner-border-sm me-2 text-primary" role="status"></div>
                                <span>Memuat data mata kuliah dari API...</span>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">URL Presentation (Slug Unik)</label>
                        <input type="text" name="url_presentation" class="form-control" value="{{ old('url_presentation') }}" required placeholder="Contoh: belajar-laravel-11">
                        <div class="form-text">URL untuk diakses publik sebagai presentasi interaktif.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">URL Finished (Slug Unik)</label>
                        <input type="text" name="url_finished" class="form-control" value="{{ old('url_finished') }}" required placeholder="Contoh: belajar-laravel-11-pdf">
                        <div class="form-text">URL untuk diakses publik sebagai PDF utuh.</div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Simpan Data</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
