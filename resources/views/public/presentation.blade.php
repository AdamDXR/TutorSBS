<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $master->judul }} - Presentation</title>
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .presentation-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        #tutorial-content {
            transition: opacity 0.3s ease;
        }
    </style>
</head>
<body>

<div class="presentation-header text-center position-relative">
    <div class="container">
        <h1 class="fw-bold">{{ $master->judul }}</h1>
        <p class="mb-3">Mata Kuliah: <span class="badge bg-warning text-dark">{{ $master->kode_makul }}</span> | By: {{ $master->creator_email }}</p>
        
        <a href="{{ route('tutorial.pdf', $master->url_finished) }}" class="btn btn-light text-primary fw-bold rounded-pill px-4 shadow-sm" target="_blank">
            📄 Download PDF Lengkap
        </a>
    </div>
</div>

<div class="container pb-5">
    <!-- Konten tutorial akan dirender di sini oleh JavaScript -->
    <div id="tutorial-content" class="row justify-content-center">
        <!-- Render awal (dari server) -->
        <div class="col-lg-8">
            @forelse($details as $i => $item)
                <div class="card mb-4 shadow-sm border-0 presentation-card animate-fade">
                    <div class="card-body p-4">
                        <span class="badge bg-primary mb-3">Langkah {{ $i + 1 }}</span>
                        
                        @if($item->text)
                            <div class="fs-5 mb-3">{!! nl2br(e($item->text)) !!}</div>
                        @endif
                        
                        @if($item->gambar)
                            <div class="text-center mb-3">
                                <img src="{{ $item->gambar }}" class="img-fluid rounded" alt="Gambar Tutorial">
                            </div>
                        @endif
                        
                        @if($item->code)
                            <div class="position-relative code-container mb-3">
                                <div class="position-absolute top-0 end-0 p-2 d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-light border-0" onclick="copyCode(this)" title="Copy Code">📋 Copy</button>
                                    <button class="btn btn-sm btn-outline-light border-0" onclick="downloadCode(this)" title="Download .txt">💾 Download</button>
                                </div>
                                <pre class="bg-dark text-light p-3 pt-5 rounded font-monospace mb-0"><code class="code-content">{{ $item->code }}</code></pre>
                            </div>
                        @endif
                        
                        @if($item->url)
                            <div class="mt-3">
                                <a href="{{ $item->url }}" target="_blank" class="btn btn-outline-primary btn-sm">🔗 Buka Referensi</a>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center text-muted">
                    <p>Belum ada materi tutorial yang ditambahkan.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script>
// =======================================================
// SMART DOM UPDATE — AJAX Polling dengan Fetch API
// =======================================================

// Variabel untuk menyimpan data JSON dari response sebelumnya.
// Digunakan untuk membandingkan apakah ada perubahan data.
let dataTerakhir = null;

// Interval polling dalam milidetik (10 detik)
const INTERVAL_POLLING = 10000;

/**
 * Fungsi utama: Mengambil data detail tutorial terbaru dari server.
 * Hanya detail dengan status 'show' yang dikembalikan oleh endpoint.
 * Jika data baru SAMA dengan data lama, DOM TIDAK akan di-update
 * untuk menghindari proses repaint layar yang tidak perlu.
 */
async function ambilDataTerbaru() {
    try {
        // --- Kirim request ke endpoint JSON internal Laravel ---
        const response = await fetch("{{ route('tutorial.data', $url) }}");
        if (!response.ok) throw new Error('Gagal mengambil data dari server');

        const hasil = await response.json();
        
        // Konversi array details menjadi string untuk perbandingan
        const dataBaruString = JSON.stringify(hasil.details);

        // --- Bandingkan data baru dengan data lama ---
        // Update DOM hanya jika ada perubahan (menghindari repaint)
        if (dataBaruString !== dataTerakhir) {
            dataTerakhir = dataBaruString; // Simpan state terbaru
            renderKeHalaman(hasil.details); // Update tampilan
            console.log('Data berubah, DOM diperbarui pada', new Date().toLocaleTimeString());
        } else {
            console.log('Tidak ada perubahan data, skip update DOM.', new Date().toLocaleTimeString());
        }
    } catch (error) {
        console.error('Error saat polling:', error);
    }
}

/**
 * Fungsi render: Membangun ulang HTML dari data JSON
 * dan memasukkannya ke dalam container #tutorial-content.
 * Transisi opacity digunakan agar pergantian konten terasa halus.
 */
function renderKeHalaman(details) {
    const container = document.getElementById('tutorial-content');
    
    if(details.length === 0) {
        container.innerHTML = '<div class="col-lg-8"><div class="text-center text-muted"><p>Belum ada materi tutorial yang ditambahkan.</p></div></div>';
        return;
    }

    let html = '<div class="col-lg-8">';
    details.forEach((item, i) => {
        html += `
            <div class="card mb-4 shadow-sm border-0 presentation-card animate-fade">
                <div class="card-body p-4">
                    <span class="badge bg-primary mb-3">Langkah ${i + 1}</span>
                    ${item.text ? `<div class="fs-5 mb-3">${escapeHtml(item.text).replace(/\\n/g, '<br>')}</div>` : ''}
                    ${item.gambar ? `<div class="text-center mb-3"><img src="${item.gambar}" class="img-fluid rounded" alt="Gambar"></div>` : ''}
                    ${item.code ? `
                        <div class="position-relative code-container mb-3">
                            <div class="position-absolute top-0 end-0 p-2 d-flex gap-2">
                                <button class="btn btn-sm btn-outline-light border-0" onclick="copyCode(this)" title="Copy Code">📋 Copy</button>
                                <button class="btn btn-sm btn-outline-light border-0" onclick="downloadCode(this)" title="Download .txt">💾 Download</button>
                            </div>
                            <pre class="bg-dark text-light p-3 pt-5 rounded font-monospace mb-0"><code class="code-content">${escapeHtml(item.code)}</code></pre>
                        </div>
                    ` : ''}
                    ${item.url ? `<div class="mt-3"><a href="${item.url}" target="_blank" class="btn btn-outline-primary btn-sm">🔗 Buka Referensi</a></div>` : ''}
                </div>
            </div>`;
    });
    html += '</div>';

    // Transisi halus saat mengganti konten
    container.style.opacity = '0.3';
    setTimeout(() => { 
        container.innerHTML = html; 
        container.style.opacity = '1'; 
    }, 300);
}

// Helper untuk mencegah XSS saat render dari JS
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Inisialisasi state pertama kali dari PHP
document.addEventListener('DOMContentLoaded', () => {
    // Ambil data awal yang dirender PHP sebagai state pertama
    dataTerakhir = JSON.stringify(@json($details));
    
    // Mulai polling
    setInterval(ambilDataTerbaru, INTERVAL_POLLING);
});

// Fitur Copy Code ke Clipboard
function copyCode(btn) {
    const codeBlock = btn.closest('.code-container').querySelector('.code-content');
    const text = codeBlock.textContent || codeBlock.innerText;
    
    navigator.clipboard.writeText(text).then(() => {
        const originalText = btn.innerHTML;
        btn.innerHTML = '✅ Copied!';
        setTimeout(() => { btn.innerHTML = originalText; }, 2000);
    }).catch(err => {
        console.error('Gagal mengcopy teks: ', err);
        alert('Gagal mengcopy teks. Silakan copy secara manual.');
    });
}

// Fitur Download Code sebagai .txt
function downloadCode(btn) {
    const codeBlock = btn.closest('.code-container').querySelector('.code-content');
    const text = codeBlock.textContent || codeBlock.innerText;
    
    const blob = new Blob([text], { type: 'text/plain' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    
    a.href = url;
    a.download = 'code_snippet.txt';
    document.body.appendChild(a);
    a.click();
    
    // Cleanup
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
}
</script>

</body>
</html>
