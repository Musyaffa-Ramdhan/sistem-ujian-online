@extends('siswa.layouts.app')

@section('title', 'Mengerjakan Ujian')
@section('active_menu', 'ujian')

@section('content')
<div class="bagianawal">
    <div class="namaMapel">
        <h4>Ujian Mata Pelajaran: {{ $ujian->mataPelajaran->nama_mapel ?? 'Ujian' }}</h4>
    </div>
    <div class="waktu">
        <h4 id="timer">Waktu: {{ $ujian->durasi }}:00</h4>
    </div>
</div>

<div class="isi">
    <div class="bagiansoal rounded-3">
        <h3>Soal <span id="currentSoal">1</span> Dari {{ $ujian->soals->count() }}</h3>
        
        <form id="ujianForm" action="{{ route('siswa.ujian.submit', $ujian->id_ujian) }}" method="POST">
            @csrf
            <div id="soalContainer">
                @foreach($ujian->soals as $index => $soal)
                <div class="soal-item" data-nomor="{{ $index + 1 }}" style="display: {{ $index == 0 ? 'block' : 'none' }}">
                    <p class="fw-bold mb-3">{!! nl2br(e($soal->soal)) !!}</p>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="jawaban[{{ $soal->id_soal }}]" value="A" id="soal{{ $soal->id_soal }}A" {{ isset($existingAnswers[$soal->id_soal]) && $existingAnswers[$soal->id_soal] == 'A' ? 'checked' : '' }}>
                        <label class="form-check-label" for="soal{{ $soal->id_soal }}A">A. {{ $soal->opsi_a }}</label>
                    </div>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="jawaban[{{ $soal->id_soal }}]" value="B" id="soal{{ $soal->id_soal }}B" {{ isset($existingAnswers[$soal->id_soal]) && $existingAnswers[$soal->id_soal] == 'B' ? 'checked' : '' }}>
                        <label class="form-check-label" for="soal{{ $soal->id_soal }}B">B. {{ $soal->opsi_b }}</label>
                    </div>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="jawaban[{{ $soal->id_soal }}]" value="C" id="soal{{ $soal->id_soal }}C" {{ isset($existingAnswers[$soal->id_soal]) && $existingAnswers[$soal->id_soal] == 'C' ? 'checked' : '' }}>
                        <label class="form-check-label" for="soal{{ $soal->id_soal }}C">C. {{ $soal->opsi_c }}</label>
                    </div>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="jawaban[{{ $soal->id_soal }}]" value="D" id="soal{{ $soal->id_soal }}D" {{ isset($existingAnswers[$soal->id_soal]) && $existingAnswers[$soal->id_soal] == 'D' ? 'checked' : '' }}>
                        <label class="form-check-label" for="soal{{ $soal->id_soal }}D">D. {{ $soal->opsi_d }}</label>
                    </div>

                    @if($soal->opsi_e)
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="jawaban[{{ $soal->id_soal }}]" value="E" id="soal{{ $soal->id_soal }}E" {{ isset($existingAnswers[$soal->id_soal]) && $existingAnswers[$soal->id_soal] == 'E' ? 'checked' : '' }}>
                        <label class="form-check-label" for="soal{{ $soal->id_soal }}E">E. {{ $soal->opsi_e }}</label>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>

            <div class="tombol">
                <button type="button" class="btn btn-primary rounded-5" id="prevBtn" disabled>Soal Sebelumnya</button>
                <button type="button" class="btn btn-primary rounded-5" id="nextBtn">Soal Selanjutnya</button>
                <button type="submit" class="btn btn-success rounded-5" id="submitBtn" style="display:none">Selesai Ujian</button>
            </div>
        </form>
    </div>

    <div class="bagiansamping rounded-3">
        <h3>Navigasi Soal</h3>
        <hr>
        <div class="nomorsoal" id="navSoal">
            @foreach($ujian->soals as $index => $soal)
            <div class="soal-nav" data-nomor="{{ $index + 1 }}" onclick="goToSoal({{ $index + 1 }})">
                {{ $index + 1 }}
            </div>
            @endforeach
        </div>
        <hr>
        <div class="keterangan">
            <div class="benar">
                <div class="petunjuk rounded-3"></div>
                <div class="petunjuk2">= Sudah dijawab</div>
            </div>
            <div class="belumdijawab">
                <div class="petunjuk rounded-3"></div>
                <div class="petunjuk2">= Belum dijawab</div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>


    .bagianawal {
        display: flex;
        background-color: royalblue;
        justify-content: space-between;
        height: 80px;
        align-items: center;
        padding: 0 20px;
    }

    .namaMapel h4, .waktu h4 {
        font-weight: 600;
        color: white;
        margin: 0;
    }

    .isi {
        display: flex;
        margin-top: 30px;
        padding: 0 20px;
    }

    .bagiansoal {
        flex: 1;
        margin-right: 20px;
        min-height: 400px;
        padding: 20px;
        background-color: white;
    }

    .bagiansoal .tombol {
        display: flex;
        justify-content: space-between;
        margin-top: 50px;
    }

    .bagiansamping {
        width: 400px;
        min-height: 470px;
        padding: 20px;
        background-color: white;
    }

    .nomorsoal {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-evenly;
    }

    .soal-nav {
        padding: 15px;
        margin: 10px;
        background-color: #f0f0f0;
        border-radius: 10px;
        width: 40px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }

    .soal-nav:hover {
        background-color: #ddd;
    }

    .soal-nav.answered {
        background-color: royalblue;
        color: white;
    }

    .benar .petunjuk {
        padding: 6px;
        width: 40px;
        height: 30px;
        background-color: royalblue;
        margin-right: 10px;
    }

    .belumdijawab .petunjuk {
        padding: 6px;
        width: 40px;
        height: 30px;
        background-color: #f0f0f0;
        margin-right: 10px;
    }

    .benar, .belumdijawab {
        display: flex;
        align-items: center;
        margin: 10px 0;
    }
</style>
@endpush

@push('scripts')
<script>
let currentSoal = 1;
const totalSoal = {{ $ujian->soals->count() }};
// Use server-side calculated remaining seconds
let timeLeft = {{ $sisaWaktu }};

function showSoal(nomor) {
    document.querySelectorAll('.soal-item').forEach(item => {
        item.style.display = 'none';
    });
    document.querySelector(`.soal-item[data-nomor="${nomor}"]`).style.display = 'block';
    document.getElementById('currentSoal').textContent = nomor;
    
    updateButtons();
}

function goToSoal(nomor) {
    currentSoal = nomor;
    showSoal(nomor);
}

function updateButtons() {
    document.getElementById('prevBtn').disabled = currentSoal === 1;
    document.getElementById('nextBtn').style.display = currentSoal === totalSoal ? 'none' : 'inline-block';
    document.getElementById('submitBtn').style.display = currentSoal === totalSoal ? 'inline-block' : 'none';
}

function checkAnswered() {
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        if (radio.checked) {
            const soalItem = radio.closest('.soal-item');
            const nomor = soalItem.dataset.nomor;
            const navItem = document.querySelector(`.soal-nav[data-nomor="${nomor}"]`);
            if (navItem) navItem.classList.add('answered');
        }
    });
}

document.getElementById('nextBtn').addEventListener('click', () => {
    if (currentSoal < totalSoal) {
        currentSoal++;
        showSoal(currentSoal);
    }
});

document.getElementById('prevBtn').addEventListener('click', () => {
    if (currentSoal > 1) {
        currentSoal--;
        showSoal(currentSoal);
    }
});

// Auto Save & Timer Logic
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

document.querySelectorAll('input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', function() {
        checkAnswered();
        
        // Auto Save via AJAX
        const soalId = this.name.replace('jawaban[', '').replace(']', '');
        const jawaban = this.value;
        const ujianId = "{{ $ujian->id_ujian }}";
        
        fetch("{{ route('siswa.ujian.save-answer') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                id_ujian: ujianId,
                id_soal: soalId,
                jawaban: jawaban
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Answer saved');
        })
        .catch(error => console.error('Error saving answer:', error));
    });
});

// Timer
setInterval(() => {
    if (timeLeft <= 0) {
        // Prevent multiple submits
        if (!window.isSubmitting) {
            window.isSubmitting = true;
            alert('Waktu habis! Ujian akan disubmit otomatis.');
            document.getElementById('ujianForm').submit();
        }
        return;
    }
    
    timeLeft--;
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    document.getElementById('timer').textContent = `Waktu: ${minutes}:${seconds.toString().padStart(2, '0')}`;
    
}, 1000);

// Init
checkAnswered(); // Mark already answered on load
updateButtons();

// ── Anti-Cheat (Integritas Ujian) ──────────────────────────
// 1. Cegah copy / cut / paste pada area soal agar siswa tidak menyalin soal/jawaban.
['copy', 'cut', 'paste'].forEach(evt => {
    document.addEventListener(evt, function (e) {
        e.preventDefault();
    });
});

// 2. Nonaktifkan klik kanan (context menu) selama ujian.
document.addEventListener('contextmenu', e => e.preventDefault());

// 3. Nonaktifkan seleksi teks pada kontainer soal.
const soalArea = document.querySelector('.bagiansoal');
if (soalArea) {
    soalArea.style.userSelect = 'none';
    soalArea.style.webkitUserSelect = 'none';
}

// 4. Deteksi siswa berpindah tab / minimize window (indikasi mencontek).
let pelanggaran = 0;
document.addEventListener('visibilitychange', function () {
    if (document.hidden && !window.isSubmitting) {
        pelanggaran++;
        alert('Peringatan ' + pelanggaran + ': Anda meninggalkan halaman ujian. ' +
              'Aktivitas ini tercatat. Tetap fokus pada layar ujian.');
    }
});
</script>
@endpush
@endsection
