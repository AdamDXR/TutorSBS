@extends('layouts.app')

@section('title', 'Detail Tutorial')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('master-tutorial.index') }}" class="text-decoration-none text-muted mb-2 d-block">&larr; Kembali ke Master Tutorial</a>
        <h3 class="fw-bold text-dark mb-0">Detail: {{ $master->judul }}</h3>
    </div>
    <a href="{{ route('detail-tutorial.create', ['master_id' => $master->id]) }}" class="btn btn-primary">
        + Tambah Detail
    </a>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <table id="detailTable" class="table table-striped table-hover dt-responsive nowrap w-100">
            <thead class="table-light">
                <tr>
                    <th>Order</th>
                    <th>Text Snippet</th>
                    <th>Gambar</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($details as $item)
                <tr>
                    <td class="fw-bold text-center">{{ $item->order }}</td>
                    <td>{{ Str::limit($item->text, 50) ?: '-' }}</td>
                    <td>
                        @if($item->gambar)
                            <a href="{{ $item->gambar }}" target="_blank" class="badge bg-info text-decoration-none">Lihat Gambar</a>
                        @else
                            <span class="badge bg-secondary">Tidak ada gambar</span>
                        @endif
                    </td>
                    <td>
                        @if($item->status == 'show')
                            <span class="badge bg-success">Show</span>
                        @else
                            <span class="badge bg-secondary">Hide</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('detail-tutorial.edit', $item->id) }}" class="btn btn-sm btn-warning text-white" title="Edit">
                            Edit
                        </a>
                        <form action="{{ route('detail-tutorial.destroy', $item->id) }}" method="POST" class="d-inline form-delete">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#detailTable').DataTable({
            responsive: true,
            order: [[0, 'asc']], // Sort default by Order column
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json',
            }
        });

        $('.btn-delete').on('click', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            Swal.fire({
                title: 'Hapus detail ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
