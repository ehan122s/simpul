<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kegiatan</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; line-height: 1.5; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 12px; }
        .table-data { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table-data th, .table-data td { padding: 6px; vertical-align: top; }
        .table-data th { width: 30%; text-align: left; }
        .table-data td { width: 70%; }
        .content-section { margin-bottom: 15px; }
        .content-section h3 { border-bottom: 1px solid #ccc; padding-bottom: 3px; font-size: 14px; }
        .signature { width: 100%; margin-top: 50px; }
        .signature-table { width: 100%; text-align: center; }
        .signature-table td { width: 50%; }
        .photos { width: 100%; text-align: center; margin-top: 20px; }
        .photo-item { display: inline-block; width: 45%; margin: 2%; }
        .photo-item img { width: 100%; max-height: 200px; object-fit: cover; border: 1px solid #ccc; }
    </style>
</head>
<body>

    <div class="header">
        <h1>LAPORAN KEGIATAN PENYULUH KEHUTANAN</h1>
        <p>Sistem Informasi Penyuluh Kehutanan (SIMPUL)</p>
    </div>

    <table class="table-data">
        <tr>
            <th>Nama Penyuluh</th>
            <td>: {{ $activity->user->name }}</td>
        </tr>
        <tr>
            <th>Judul Kegiatan</th>
            <td>: {{ $activity->title }}</td>
        </tr>
        <tr>
            <th>Tanggal Pelaksanaan</th>
            <td>: {{ $activity->activity_date->format('d F Y') }}</td>
        </tr>
        <tr>
            <th>Lokasi</th>
            <td>: {{ $activity->location }}, Desa {{ $activity->village }}, Kec. {{ $activity->district }}</td>
        </tr>
        <tr>
            <th>Kelompok Sasaran</th>
            <td>: {{ $activity->farmer_group_name }} ({{ $activity->participant_count }} Peserta)</td>
        </tr>
    </table>

    <div class="content-section">
        <h3>A. Materi Penyuluhan</h3>
        <p>{{ $activity->material }}</p>
    </div>

    <div class="content-section">
        <h3>B. Hasil Kegiatan</h3>
        <p>{{ $activity->result }}</p>
    </div>

    <div class="content-section">
        <h3>C. Kendala & Tindak Lanjut</h3>
        <p><strong>Kendala:</strong> {{ $activity->obstacle ?? 'Tidak ada' }}</p>
        <p><strong>Tindak Lanjut:</strong> {{ $activity->follow_up ?? 'Tidak ada' }}</p>
    </div>

    <!-- Dokumentasi Foto (Halaman Baru jika perlu) -->
    @if($activity->photos->count() > 0)
    <div style="page-break-before: always;">
        <h3>D. Dokumentasi Kegiatan</h3>
        <div class="photos">
            @foreach($activity->photos as $photo)
                <div class="photo-item">
                    <!-- Path ini harus disesuaikan dengan storage path laravel agar terbaca DomPDF -->
                    <img src="{{ storage_path('app/' . $photo->file_path) }}" alt="Foto Kegiatan">
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="signature">
        <table class="signature-table">
            <tr>
                <td></td>
                <td>
                    <p>{{ $activity->regency }}, {{ $activity->activity_date->format('d F Y') }}</p>
                    <p>Penyuluh Kehutanan,</p>
                    <br><br><br>
                    <p><strong><u>{{ $activity->user->name }}</u></strong></p>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
