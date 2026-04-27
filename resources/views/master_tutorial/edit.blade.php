@extends('layouts.app')

@section('title', 'Edit Master Tutorial')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-dark">Edit Master Tutorial</h3>
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

                <form action="{{ route('master-tutorial.update', $data->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Tutorial</label>
                        <input type="text" name="judul" class="form-control" value="{{ old('judul', $data->judul) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Mata Kuliah</label>
                        @if(isset($makul['data']) && is_array($makul['data']))
                            <select name="kode_makul" class="form-select" required>
                                <option value="">-- Pilih Mata Kuliah --</option>
                                @foreach($makul['data'] as $m)
                                    <option value="{{ $m['kdmk'] }}" 
                                        {{ old('kode_makul', $data->kode_makul) == $m['kdmk'] ? 'selected' : '' }}>
                                        {{ $m['kdmk'] }} - {{ $m['nama'] }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            {{-- Fix 5: Tampilkan spinner jika data makul belum tersedia --}}
                            <div class="d-flex align-items-center text-muted p-2 border rounded bg-light">
                                <div class="spinner-border spinner-border-sm me-2 text-primary" role="status"></div>
                                <span>Memuat data mata kuliah dari API...</span>
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">URL Presentation (Slug Unik)</label>
                        <input type="text" name="url_presentation" class="form-control" value="{{ old('url_presentation', $data->url_presentation) }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">URL Finished (Slug Unik)</label>
                        <input type="text" name="url_finished" class="form-control" value="{{ old('url_finished', $data->url_finished) }}" required>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 py-2 fw-bold text-white">Update Data</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
