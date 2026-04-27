<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>PDF - {{ $master->judul }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2a5298;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            color: #1e3c72;
        }
        .meta {
            color: #666;
            font-size: 0.9em;
            margin-top: 5px;
        }
        .step {
            margin-bottom: 40px;
            page-break-inside: avoid;
        }
        .step-title {
            background-color: #f4f4f4;
            padding: 5px 10px;
            font-weight: bold;
            border-left: 4px solid #2a5298;
            margin-bottom: 15px;
        }
        .step-text {
            margin-bottom: 15px;
        }
        .step-img {
            text-align: center;
            margin-bottom: 15px;
        }
        .step-img img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
        }
        .step-code {
            background-color: #2d2d2d;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 4px;
            font-family: monospace;
            white-space: pre-wrap;
            margin-bottom: 15px;
            font-size: 0.9em;
        }
        .step-url {
            font-style: italic;
            font-size: 0.9em;
            color: #2a5298;
        }
        .badge-hide {
            color: #dc3545;
            font-size: 0.8em;
            font-weight: normal;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ $master->judul }}</h1>
        <div class="meta">
            Mata Kuliah: {{ $master->kode_makul }} | Dibuat oleh: {{ $master->creator_email }}<br>
            Dicetak pada: {{ now()->format('d M Y H:i') }}
        </div>
    </div>

    @if($details->isEmpty())
        <p style="text-align:center; color:#999;">Belum ada materi tutorial.</p>
    @else
        @foreach($details as $i => $item)
            <div class="step">
                <div class="step-title">
                    Langkah {{ $item->order }}
                    @if($item->status == 'hide')
                        <span class="badge-hide">(Hidden/Internal Only)</span>
                    @endif
                </div>

                @if($item->text)
                    <div class="step-text">{!! nl2br(e($item->text)) !!}</div>
                @endif

                @if($item->gambar)
                    @php
                        $imageSrc = $item->gambar;
                        // Jika gambar dari storage lokal, ubah ke absolute path server agar Dompdf tidak hang di php artisan serve
                        if (str_contains($item->gambar, '/storage/')) {
                            // Ambil path relatifnya
                            $relativePath = parse_url($item->gambar, PHP_URL_PATH);
                            // Sesuaikan dengan public_path Laravel (biasanya path dimulai dari /storage, jadi hapus slash awal)
                            $imageSrc = public_path(ltrim($relativePath, '/'));
                        }
                    @endphp
                    <div class="step-img">
                        <img src="{{ $imageSrc }}" alt="Gambar Langkah {{ $item->order }}">
                    </div>
                @endif

                @if($item->code)
                    <div class="step-code">{{ $item->code }}</div>
                @endif

                @if($item->url)
                    <div class="step-url">Referensi Tambahan: <a href="{{ $item->url }}">{{ $item->url }}</a></div>
                @endif
            </div>
        @endforeach
    @endif

</body>
</html>
