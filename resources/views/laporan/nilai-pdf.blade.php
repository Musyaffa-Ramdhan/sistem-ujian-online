<!DOCTYPE html>
<html>
<head>
    <title>Laporan Hasil Ujian</title>
    <style>
        /* CSS Sederhana untuk PDF Class Report */
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .details { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { margin-top: 30px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Hasil Ujian</h2>
        <h3>SMP Negeri Example App</h3>
    </div>

    {{-- Ringkasan Detail Ujian Satu Kelas --}}
    <div class="details">
        <p><strong>Ujian:</strong> {{ $ujian->nama_ujian }}</p>
        <p><strong>Mata Pelajaran:</strong> {{ $ujian->mataPelajaran->nama_mapel }}</p>
        <p><strong>Kelas:</strong> {{ $ujian->kelas->nama_kelas }}</p>
        <p><strong>Guru:</strong> {{ $ujian->guru->nama }}</p>
        <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($ujian->tanggal_ujian)->format('d-m-Y') }}</p>
        <p><strong>Total Siswa Mengerjakan:</strong> {{ $results->count() }}</p>
    </div>

    {{-- Tabel Nilai Kolektif --}}
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Benar</th>
                <th>Salah</th>
                <th>Nilai Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $index => $result)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $result->siswa->nama }}</td>
                <td>{{ $result->total_benar }}</td>
                <td>{{ $result->total_salah }}</td>
                <td>{{ $result->nilai }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d-m-Y H:i') }}</p>
        <br><br><br>
        <p>( {{ $ujian->guru->nama }} )</p>
    </div>
</body>
</html>
