@extends('layouts.app')

@section('content')
<div class="row position-relative">

    <!-- PROTEKSI UI 1: Menampilkan Pesan Error Validasi Request -->
    @if($errors->any())
    <div class="col-md-12 mb-3">
        <div class="alert alert-danger fw-bold shadow-sm rounded-4 border-0 border-start border-danger border-5">
            <i class="fas fa-exclamation-triangle me-2"></i> Terjadi kesalahan saat mengumpulkan ujian:
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="col-md-12 mb-4 sticky-top pt-3 z-3">
        <div class="card shadow-lg border-0 bg-white border-top border-5 border-info rounded-4">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3 p-3">
                <div>
                    <h4 class="mb-1 fw-bold text-info"><i class="fas fa-file-signature me-2"></i>{{ $ujian->judul_ujian }}</h4>
                    <p class="text-muted mb-0 fw-bold"><i class="fas fa-book me-1"></i> {{ $ujian->pelajaran->nama_pelajaran }}</p>
                </div>
                <div class="text-end bg-light p-2 rounded border border-danger shadow-sm">
                    <span class="text-danger fw-bold d-block mb-1" style="font-size: 0.9rem;">SISA WAKTU UJIAN</span>
                    <span id="countdown_timer" class="fw-bold fs-4 text-danger font-monospace">00:00:00</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <form action="{{ route('siswa.ujian.submit', $ujian->id) }}" method="POST" id="formUjian">
            @csrf

            @forelse($ujian->soal as $index => $soal)
            <!-- Dinamis: border-warning untuk essay, border-info untuk Pilihan Ganda (selaras gaya guru) -->
            <div class="card shadow-sm border-0 mb-4 rounded-4 border-start border-5 border-{{ $soal->jenis_soal == 'essay' ? 'warning' : 'info' }}">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-dark mb-0">{{ $index + 1 }}. {{ $soal->pertanyaan }}</h5>
                        @if($soal->jenis_soal == 'essay')
                            <span class="badge bg-warning text-dark"><i class="fas fa-pen-nib me-1"></i> Essay</span>
                        @else
                            <span class="badge bg-info"><i class="fas fa-list-ul me-1"></i> Pilihan Ganda</span>
                        @endif
                    </div>

                    @if($soal->gambar_soal)
                    <div class="mb-4">
                        <!-- CLEAN CODE: Menggunakan .soal-image dari custom.css -->
                        <img src="{{ asset('uploads/soal/' . $soal->gambar_soal) }}" class="img-fluid rounded shadow-sm border soal-image" alt="Gambar Soal">
                    </div>
                    @endif

                    <div class="mt-3">
                        @if($soal->jenis_soal == 'pilihan_ganda')
                            <p class="text-muted small fst-italic mb-2"><i class="fas fa-info-circle"></i> Pilih jawaban (Bisa menceklis lebih dari satu jika perlu).</p>

                            @php
                                $pilihan = collect([
                                    ['kode' => 'A', 'teks' => $soal->pilihan_a],
                                    ['kode' => 'B', 'teks' => $soal->pilihan_b],
                                    ['kode' => 'C', 'teks' => $soal->pilihan_c],
                                    ['kode' => 'D', 'teks' => $soal->pilihan_d],
                                ]);

                                if ($ujian->acak_jawaban) {
                                    $seedJawaban = $ujian->id . '-' . Auth::id() . '-' . $soal->id;
                                    $pilihan = $pilihan
                                        ->sortBy(fn ($item) => crc32($seedJawaban . '-' . $item['kode']))
                                        ->values();
                                }
                            @endphp

                            @foreach($pilihan as $opsiIndex => $opsi)
                                @php
                                    $labelTampil = chr(65 + $opsiIndex);
                                    $opsiId = 'soal_' . $soal->id . '_' . $opsi['kode'];
                                @endphp
                                <div class="form-check mb-3 bg-light p-2 rounded border border-1">
                                    <input class="form-check-input ms-1 border-primary" type="checkbox" name="jawaban[{{ $soal->id }}][]" id="{{ $opsiId }}" value="{{ $opsi['kode'] }}">
                                    <label class="form-check-label w-100 ms-2 fw-bold" for="{{ $opsiId }}">{{ $labelTampil }}. {{ $opsi['teks'] }}</label>
                                </div>
                            @endforeach

                        @else
                            <p class="text-muted small fst-italic mb-2"><i class="fas fa-info-circle"></i> Ketik jawaban Anda pada kotak di bawah ini.</p>
                            <!-- ATRIBUT 'required' DIHAPUS: Supaya validasi "nullable" di request backend berlaku (siswa boleh tidak menjawab) -->
                            <textarea name="jawaban[{{ $soal->id }}]" class="form-control form-control-lg shadow-sm border-warning" rows="5" placeholder="Ketik jawaban essay Anda di sini..."></textarea>
                        @endif

                        <!-- PROTEKSI UI 2: Menampilkan Error Spesifik per Soal jika dimanipulasi -->
                        @error('jawaban.' . $soal->id)
                            <div class="text-danger mt-2 fw-bold small"><i class="fas fa-exclamation-circle me-1"></i> Data jawaban tidak valid!</div>
                        @enderror
                    </div>

                </div>
            </div>
            @empty
            <div class="alert alert-warning shadow-sm fw-bold p-4 text-center">
                <i class="fas fa-exclamation-triangle fa-2x mb-2"></i><br>
                Maaf, guru belum memasukkan soal untuk ujian ini. Silakan hubungi guru yang bersangkutan.
            </div>
            @endforelse

            @if($ujian->soal->count() > 0)
            <div class="card shadow-sm border-0 mt-4 mb-5 rounded-4">
                <div class="card-body text-center p-4 bg-light">
                    <h5 class="text-danger mb-3 fw-bold"><i class="fas fa-exclamation-circle me-1"></i> Periksa kembali jawaban Anda!</h5>
                    <p class="text-muted">Pastikan semua soal sudah terisi sebelum menekan tombol kumpulkan.</p>

                    <!-- Tombol kumpulkan, memakai warna info agar selaras gaya guru -->
                    <!-- PROTEKSI FRONTEND: ID disematkan untuk script JS Anti-Double Click -->
                    <button type="submit" id="btnSubmitUjian" class="btn btn-lg btn-info text-white px-5 py-3 fw-bold shadow mt-2 w-100 fs-5">
                        <i class="fas fa-paper-plane me-2"></i> Kumpulkan Ujian Sekarang
                    </button>
                </div>
            </div>
            @endif
        </form>
    </div>
</div>

<script>
    // Set waktu penutupan ujian dari database
    var batasWaktu = new Date("{{ \Carbon\Carbon::parse($ujian->waktu_selesai)->format('M d, Y H:i:s') }}").getTime();

    // Update timer setiap 1 detik
    var timerInterval = setInterval(function() {
        var sekarang = new Date().getTime();
        var selisih = batasWaktu - sekarang;

        var hours = Math.floor((selisih % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((selisih % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((selisih % (1000 * 60)) / 1000);

        hours = hours < 10 ? "0" + hours : hours;
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        document.getElementById("countdown_timer").innerHTML = hours + ":" + minutes + ":" + seconds;

        // Jika waktu habis, paksa form tersubmit
        if (selisih < 0) {
            clearInterval(timerInterval);
            document.getElementById("countdown_timer").innerHTML = "WAKTU HABIS";
            alert("Waktu ujian telah habis! Sistem akan otomatis mengumpulkan jawaban Anda.");

            // Kunci tombol agar tidak di-spam click saat auto-submit
            let btn = document.getElementById("btnSubmitUjian");
            if(btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Memproses...';
            }
            document.getElementById("formUjian").submit();
        }
    }, 1000);

    // PROTEKSI UI 3: JavaScript Anti-Double Click (Race Condition Shield)
    document.getElementById("formUjian").addEventListener("submit", function(e) {
        let btn = document.getElementById("btnSubmitUjian");

        // Pindahkan fungsi confirm ke dalam event listener agar tombol tidak terlanjur disable jika user pilih 'Batal/Cancel'
        if (!btn.disabled) {
            if (confirm('Yakin ingin mengumpulkan ujian sekarang? Anda tidak bisa mengulangnya lagi atau mengubah jawaban.')) {
                // Kunci tombol secara visual dan fungsional
                btn.disabled = true;
                btn.classList.add("opacity-75");
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mengumpulkan Jawaban...';
                // Biarkan event submit berlanjut ke backend
            } else {
                // Jika user membatalkan confirm, cegah pengiriman form
                e.preventDefault();
            }
        } else {
            // Jika tombol sudah berstatus disabled (misal user spam klik), tolak event kirimnya
            e.preventDefault();
        }
    });
</script>
@endsection
