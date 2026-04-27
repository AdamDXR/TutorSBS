@extends('layouts.app')

@section('title', 'Master Tutorial')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-dark">Daftar Master Tutorial</h2>
    <a href="{{ route('master-tutorial.create') }}" class="btn btn-primary">
        + Tambah Data
    </a>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <table id="masterTable" class="table table-striped table-hover dt-responsive nowrap w-100">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Kode Makul</th>
                    <th>Presentation</th>
                    <th>PDF</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tutorials as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="fw-bold">{{ $item->judul }}</td>
                    <td><span class="badge bg-secondary">{{ $item->kode_makul }}</span></td>
                    <td>
                        <a href="{{ route('tutorial.show', $item->url_presentation) }}" target="_blank" class="text-decoration-none">
                            🔗 {{ $item->url_presentation }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('tutorial.pdf', $item->url_finished) }}" target="_blank" class="text-decoration-none text-danger">
                            📄 {{ $item->url_finished }}
                        </a>
                    </td>
                    <td class="text-center">
                        <a href="{{ route('detail-tutorial.byMaster', $item->id) }}" class="btn btn-sm btn-info text-white" title="Kelola Detail">
                            Kelola Detail
                        </a>
                        <a href="{{ route('master-tutorial.edit', $item->id) }}" class="btn btn-sm btn-warning text-white" title="Edit">
                            Edit
                        </a>
                        <form action="{{ route('master-tutorial.destroy', $item->id) }}" method="POST" class="d-inline form-delete">
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
    // Inisialisasi DataTables dengan ekstensi Responsive
    $(document).ready(function() {
        $('#masterTable').DataTable({
            responsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json',
            }
        });

        // Konfirmasi hapus data menggunakan SweetAlert2
        $('.btn-delete').on('click', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            Swal.fire({
                title: 'Hapus data ini?',
                text: "Semua detail tutorial terkait juga akan ikut terhapus!",
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
