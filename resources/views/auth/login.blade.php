<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TutorSBS</title>
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card login-card p-4 animate-fade">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-primary">TutorSBS</h3>
                    <p class="text-muted">Silakan login untuk mengelola tutorial</p>
                </div>

                <form id="loginForm" method="POST" action="{{ route('login.process') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="aprilyani.safitri@gmail.com" required autofocus>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="123456" required>
                    </div>
                    
                    {{-- Fix 5: Loading state saat form dikirim --}}
                    <button type="submit" id="btnLogin" class="btn btn-primary w-100 py-2">
                        <span id="btnText">Login</span>
                        <span id="btnSpinner" class="spinner-border spinner-border-sm d-none ms-2" role="status"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Fix 5: Spinner Script --}}
<script>
document.getElementById('loginForm').addEventListener('submit', function() {
    // Nonaktifkan tombol dan tampilkan spinner saat proses login
    const btn = document.getElementById('btnLogin');
    btn.disabled = true;
    document.getElementById('btnText').textContent = 'Memproses...';
    document.getElementById('btnSpinner').classList.remove('d-none');
});
</script>

{{-- Fix 7: Flashdata 401 + SweetAlert2 --}}
@if(session('error'))
<script>
// Tampilkan pesan error dari flashdata (misal: sesi API berakhir)
Swal.fire({
    icon: 'error',
    title: 'Gagal',
    text: '{{ session("error") }}',
    confirmButtonColor: '#d33'
});
</script>
@endif

@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: '{{ session("success") }}',
    confirmButtonColor: '#28a745'
});
</script>
@endif

</body>
</html>
