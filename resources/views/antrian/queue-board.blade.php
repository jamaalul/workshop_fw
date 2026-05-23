<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Papan Antrian — {{ config('app.name') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <style>
        body { background-color: #f2edf3; min-height: 100vh; padding: 2rem 0; }
        
        .board-header { margin-bottom: 2rem; }
        
        .current-called-card {
            background: linear-gradient(135deg, #da8cff, #9a55ff);
            border-radius: 16px;
            padding: 4rem 2rem;
            color: white;
            text-align: center;
            box-shadow: 0 8px 24px rgba(154, 85, 255, 0.3);
        }
        .current-num-big {
            font-size: clamp(8rem, 15vw, 12rem);
            font-weight: 900;
            line-height: 1;
            letter-spacing: -2px;
            margin: 1rem 0;
            text-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        
        .queue-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border-radius: 10px;
            font-weight: 800;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        .queue-number.waiting-num {
            background: rgba(182, 109, 255, 0.15);
            border: 1px solid rgba(182, 109, 255, 0.3);
            color: #b66dff;
        }
        .queue-row {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 15px;
            border-radius: 8px;
            border-bottom: 1px solid #f1f5f9;
        }
        .queue-row:last-child { border-bottom: none; }
        
        .live-badge {
            background: #e6f7eb;
            color: #1bcfb4;
            border-radius: 999px;
            padding: 6px 14px;
            font-weight: bold;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .live-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: #1bcfb4;
        }

        .sound-bar {
            position: fixed;
            bottom: 1.5rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 100;
        }
        .sound-activate-btn {
            background: #ffbf36;
            border: none;
            color: white;
            border-radius: 999px;
            padding: 10px 24px;
            font-weight: 700;
            box-shadow: 0 5px 15px rgba(255, 191, 54, 0.4);
            transition: all 0.2s;
        }
        .sound-activate-btn:hover { background: #f2a600; }
        
        .waiting-list-wrapper { max-height: 600px; overflow-y: auto; }
        .waiting-list-wrapper::-webkit-scrollbar { width: 6px; }
        .waiting-list-wrapper::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body>
    <div class="container-fluid px-4 px-md-5">
        <!-- Header -->
        <div class="board-header d-flex justify-content-between align-items-center bg-white p-4 rounded shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-gradient-primary rounded p-2 text-white shadow-sm">
                    <i class="mdi mdi-monitor mdi-24px"></i>
                </div>
                <div>
                    <h3 class="mb-0 fw-bold text-dark">Papan Antrian</h3>
                    <p class="text-muted mb-0 small">{{ config('app.name') }}</p>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <small class="text-muted d-flex align-items-center gap-2">
                    <div id="sseDot" style="width:8px;height:8px;border-radius:50%;background:#adb5bd;"></div>
                    <span id="sseStatusText">Menghubungkan...</span>
                </small>
                <div class="live-badge shadow-sm">
                    <div class="live-dot"></div> LIVE
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Current Panel -->
            <div class="col-lg-7 col-xl-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex flex-column justify-content-center p-5">
                        <div class="text-center mb-3">
                            <span class="badge bg-outline-primary px-3 py-2 rounded-pill fw-bold text-uppercase tracking-wider">
                                <i class="mdi mdi-bullhorn me-1"></i> Sedang Dipanggil
                            </span>
                        </div>
                        
                        <div id="currentSection" class="text-center">
                            @if ($state['current'])
                                <div class="current-called-card my-4">
                                    <div class="current-num-big" id="currentNumber">{{ str_pad($state['current']['number'], 3, '0', STR_PAD_LEFT) }}</div>
                                </div>
                                <h2 class="fw-bold text-dark mt-3" id="currentName">{{ $state['current']['name'] }}</h2>
                                <p class="text-muted fs-5"><i class="mdi mdi-arrow-right-circle me-1"></i> Silakan menuju loket</p>
                            @else
                                <div class="py-5 my-5 text-muted" id="emptyState">
                                    <i class="mdi mdi-timer-sand mdi-48px d-block mb-3 opacity-50"></i>
                                    <h4>Belum ada antrian dipanggil</h4>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Waiting Panel -->
            <div class="col-lg-5 col-xl-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-0">
                        <div class="d-flex justify-content-between align-items-center p-4 border-bottom">
                            <h4 class="card-title mb-0">Antrian Berikutnya</h4>
                            <span class="badge badge-primary rounded-pill px-3 py-2 fs-6 shadow-sm" id="waitingCount">{{ count($state['waiting']) }}</span>
                        </div>
                        
                        <div class="waiting-list-wrapper p-3" id="waitingList">
                            @forelse($state['waiting'] as $item)
                                <div class="queue-row">
                                    <div class="queue-number waiting-num">{{ str_pad($item['number'], 3, '0', STR_PAD_LEFT) }}</div>
                                    <h5 class="mb-0 fw-semibold text-dark">{{ $item['name'] }}</h5>
                                </div>
                            @empty
                                <div class="text-center py-5 text-muted h-100 d-flex flex-column justify-content-center" id="noWaiting">
                                    <i class="mdi mdi-check-circle-outline mdi-48px mb-2"></i>
                                    <p>Tidak ada antrian menunggu</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sound Activation -->
    <div class="sound-bar">
        <button class="sound-activate-btn" id="soundBtn" onclick="activateSound()">
            <i class="mdi mdi-volume-off me-1"></i> Aktifkan Suara
        </button>
    </div>

    <!-- Audio element -->
    <audio id="dingAudio" preload="auto">
        <source src="{{ asset('audio/beep.mp3') }}" type="audio/mpeg">
    </audio>

    <script>
        let soundEnabled = false;
        let lastCalledNumber = {{ $state['current']['number'] ?? 'null' }};
        let lastCalledName   = '{{ addslashes($state['current']['name'] ?? '') }}';

        // ── Sound activation ────────────────────────────────────────────────
        function activateSound() {
            soundEnabled = true;
            const btn = document.getElementById('soundBtn');
            btn.outerHTML = `
                <div class="badge badge-success px-4 py-2 rounded-pill shadow-sm fs-6">
                    <i class="mdi mdi-volume-high me-1"></i> Suara Aktif
                </div>`;
        }

        // ── TTS ─────────────────────────────────────────────────────────────
        function speakQueue(number, name) {
            if (!soundEnabled) return;

            const audio = document.getElementById('dingAudio');
            audio.currentTime = 0;

            audio.onended = function () {
                const utterance = new SpeechSynthesisUtterance(
                    `Nomor antrian ${number}. ${name}, silakan masuk.`
                );
                utterance.lang   = 'id-ID';
                utterance.rate   = 0.85;
                utterance.pitch  = 1.0;
                utterance.volume = 1.0;
                window.speechSynthesis.cancel();
                window.speechSynthesis.speak(utterance);
            };

            audio.play().catch(() => {
                // fallback: TTS directly if audio fails
                const utterance = new SpeechSynthesisUtterance(
                    `Nomor antrian ${number}. ${name}, silakan masuk.`
                );
                utterance.lang   = 'id-ID';
                utterance.rate   = 0.85;
                utterance.pitch  = 1.0;
                utterance.volume = 1.0;
                window.speechSynthesis.cancel();
                window.speechSynthesis.speak(utterance);
            });
        }

        // ── DOM update ──────────────────────────────────────────────────────
        function pad(n) { return String(n).padStart(3, '0'); }

        function updateUI(data) {
            // ── Current number ──
            const section = document.getElementById('currentSection');
            const current = data.current;

            if (current) {
                const numStr = pad(current.number);

                // Detect new call
                if (current.number !== lastCalledNumber) {
                    speakQueue(current.number, current.name);
                    lastCalledNumber = current.number;
                    lastCalledName   = current.name;
                }

                section.innerHTML = `
                    <div class="current-called-card my-4">
                        <div class="current-num-big" id="currentNumber">${numStr}</div>
                    </div>
                    <h2 class="fw-bold text-dark mt-3" id="currentName">${current.name}</h2>
                    <p class="text-muted fs-5"><i class="mdi mdi-arrow-right-circle me-1"></i> Silakan menuju loket</p>`;
            } else {
                lastCalledNumber = null;
                lastCalledName   = '';
                section.innerHTML = `
                    <div class="py-5 my-5 text-muted" id="emptyState">
                        <i class="mdi mdi-timer-sand mdi-48px d-block mb-3 opacity-50"></i>
                        <h4>Belum ada antrian dipanggil</h4>
                    </div>`;
            }

            // ── Waiting list ──
            const wrapper = document.getElementById('waitingList');
            document.getElementById('waitingCount').textContent = data.waiting.length;

            if (data.waiting.length === 0) {
                wrapper.innerHTML = `<div class="text-center py-5 text-muted h-100 d-flex flex-column justify-content-center" id="noWaiting">
                    <i class="mdi mdi-check-circle-outline mdi-48px mb-2"></i>
                    <p>Tidak ada antrian menunggu</p>
                </div>`;
            } else {
                wrapper.innerHTML = data.waiting.map(item => `
                    <div class="queue-row">
                        <div class="queue-number waiting-num">${pad(item.number)}</div>
                        <h5 class="mb-0 fw-semibold text-dark">${item.name}</h5>
                    </div>`).join('');
            }
        }

        // ── SSE ─────────────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', function () {
            const dot      = document.getElementById('sseDot');
            const statusTx = document.getElementById('sseStatusText');

            const source = new EventSource('{{ route('antrian.sse') }}');

            source.addEventListener('queue-update', function (e) {
                dot.style.background = '#1bcfb4';
                statusTx.textContent = 'Terhubung';
                try {
                    const data = JSON.parse(e.data);
                    updateUI(data);
                } catch (_) {}
            });

            source.onerror = function () {
                dot.style.background = '#fe7c96';
                statusTx.textContent = 'Menyambung ulang...';
            };
        });
    </script>
</body>
</html>
