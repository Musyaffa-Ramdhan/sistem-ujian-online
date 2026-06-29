<!DOCTYPE html>
<html>
<head>
    <title>Laporan Hasil Ujian Individu</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .details { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { margin-top: 30px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Hasil Ujian Individu</h2>
        <h3>SMP Negeri Example App</h3>
    </div>

    <div class="details">
        <p><strong>Nama Siswa:</strong> {{ $siswa->nama }}</p>
        <p><strong>ID Siswa:</strong> {{ $siswa->id_siswa }}</p>
        <p><strong>Kelas:</strong> {{ $siswa->kelas->nama_kelas }}</p>
        <p><strong>Ujian:</strong> {{ $hasil->ujian->nama_ujian }}</p>
        <p><strong>Mata Pelajaran:</strong> {{ $hasil->ujian->mataPelajaran->nama_mapel }}</p>
        <p><strong>Guru:</strong> {{ $hasil->ujian->guru->nama }}</p>
        <p><strong>Tanggal Ujian:</strong> {{ \Carbon\Carbon::parse($hasil->ujian->tanggal_ujian)->format('d-m-Y') }}</p>
        <p><strong>Tanggal Selesai:</strong> {{ \Carbon\Carbon::parse($hasil->created_at)->format('d-m-Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Aspek</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Total Benar</td>
                <td>{{ $hasil->total_benar }}</td>
            </tr>
            <tr>
                <td>Total Salah</td>
                <td>{{ $hasil->total_salah }}</td>
            </tr>
            <tr>
                <td>Nilai Akhir</td>
                <td>{{ $hasil->nilai }}</td>
            </tr>
            <tr>
                <td>Status</td>
                <td>{{ $hasil->nilai >= 75 ? 'Lulus' : 'Tidak Lulus' }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d-m-Y H:i') }}</p>
        <br><br>
        <p>( {{ $siswa->nama }} )</p>
    </div>
</body>
</html>