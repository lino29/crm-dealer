<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scan Member Card') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-4">

            {{-- ===== Alert Banner ===== --}}
            <div id="alert-container" class="hidden">
                <div id="alert-message"
                     class="p-4 rounded-xl text-white font-semibold text-center shadow-sm text-sm"></div>
            </div>

            {{-- ===== Main Card ===== --}}
            <div class="bg-white shadow-sm sm:rounded-2xl overflow-hidden">
                <div class="p-6 flex flex-col items-center gap-5">

                    {{-- ── STATE: idle ─────────────────────────────────── --}}
                    <div id="state-idle" class="w-full max-w-md flex flex-col items-center gap-4">
                        <div class="w-16 h-16 bg-indigo-50 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-500 text-center">
                            Klik tombol di bawah untuk mendeteksi kamera yang terhubung pada perangkat Anda.
                        </p>
                        <button id="btn-detect"
                                class="w-full flex items-center justify-center gap-2 px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow transition text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Deteksi Kamera
                        </button>
                    </div>

                    {{-- ── STATE: camera-select ─────────────────────────── --}}
                    <div id="state-select" class="hidden w-full max-w-md flex flex-col gap-4">

                        {{-- Camera Dropdown --}}
                        <div>
                            <label for="camera-select"
                                   class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">
                                📷 Pilih Kamera
                            </label>
                            <select id="camera-select"
                                    class="w-full rounded-xl border-gray-300 bg-gray-50 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm py-2.5">
                                {{-- Options filled dynamically --}}
                            </select>
                            <p id="camera-count-label" class="text-xs text-gray-400 mt-1"></p>
                        </div>

                        {{-- Action buttons --}}
                        <div class="flex gap-3">
                            <button id="btn-back"
                                    class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                                ← Kembali
                            </button>
                            <button id="btn-start"
                                    class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Mulai Scan
                            </button>
                        </div>
                    </div>

                    {{-- ── STATE: scanning ─────────────────────────────── --}}
                    <div id="state-scanning" class="hidden w-full flex flex-col items-center gap-4">

                        {{-- Active camera label --}}
                        <div class="flex items-center gap-2 self-start">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded-full border border-green-200">
                                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                Kamera Aktif
                            </span>
                            <span id="active-camera-label" class="text-xs text-gray-500 truncate max-w-[220px]"></span>
                        </div>

                        {{-- QR Reader --}}
                        <div id="reader"
                             class="w-full max-w-lg bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl overflow-hidden"
                             style="min-height: 300px;"></div>

                        {{-- Stop + switch buttons --}}
                        <div class="flex gap-3 w-full max-w-lg">
                            <button id="btn-switch"
                                    class="flex-1 px-4 py-2.5 text-sm font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-xl border border-indigo-200 transition">
                                🔄 Ganti Kamera
                            </button>
                            <button id="btn-stop"
                                    class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-red-500 hover:bg-red-600 rounded-xl shadow transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                                </svg>
                                Hentikan
                            </button>
                        </div>
                    </div>

                </div>{{-- /p-6 --}}
            </div>{{-- /card --}}

            {{-- ===== Manual Token Input ===== --}}
            <div class="bg-white shadow-sm sm:rounded-2xl p-6">
                <p class="text-sm font-semibold text-gray-600 mb-3 text-center">Atau masukkan token secara manual:</p>
                <form id="manual-scan-form" class="flex gap-2">
                    <input type="text" id="manual_token"
                           class="flex-1 rounded-xl border border-gray-300 py-2 px-3 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition"
                           placeholder="Masukkan QR Token">
                    <button type="submit"
                            class="px-5 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-semibold rounded-xl transition shadow">
                        Submit
                    </button>
                </form>
            </div>

        </div>
    </div>

    <!-- html5-qrcode library -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        // ── DOM refs ────────────────────────────────────────────────────
        const alertContainer    = document.getElementById('alert-container');
        const alertMessage      = document.getElementById('alert-message');

        const stateIdle         = document.getElementById('state-idle');
        const stateSelect       = document.getElementById('state-select');
        const stateScanning     = document.getElementById('state-scanning');

        const btnDetect         = document.getElementById('btn-detect');
        const btnBack           = document.getElementById('btn-back');
        const btnStart          = document.getElementById('btn-start');
        const btnStop           = document.getElementById('btn-stop');
        const btnSwitch         = document.getElementById('btn-switch');
        const cameraSelect      = document.getElementById('camera-select');
        const cameraCountLabel  = document.getElementById('camera-count-label');
        const activeCameraLabel = document.getElementById('active-camera-label');

        // ── State ───────────────────────────────────────────────────────
        let html5QrCode  = new Html5Qrcode("reader");
        let detectedCams = [];   // [{id, label}, ...]
        let isScanning   = false;
        let canValidate  = true;

        // ── Helpers ─────────────────────────────────────────────────────
        function showAlert(msg, type = 'error') {
            alertContainer.classList.remove('hidden');
            alertMessage.textContent = msg;
            alertMessage.className = `p-4 rounded-xl text-white font-semibold text-center shadow-sm text-sm ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
        }
        function hideAlert() { alertContainer.classList.add('hidden'); }

        function goTo(state) {
            stateIdle.classList.add('hidden');
            stateSelect.classList.add('hidden');
            stateScanning.classList.add('hidden');

            if (state === 'idle')     stateIdle.classList.remove('hidden');
            if (state === 'select')   stateSelect.classList.remove('hidden');
            if (state === 'scanning') stateScanning.classList.remove('hidden');
        }

        function stopScanner() {
            if (!isScanning) return Promise.resolve();
            return html5QrCode.stop().then(() => { isScanning = false; });
        }

        // ── Populate camera dropdown ─────────────────────────────────────
        function populateCameraDropdown(cams) {
            cameraSelect.innerHTML = '';
            cams.forEach((cam, i) => {
                const opt       = document.createElement('option');
                opt.value       = cam.id;
                // Use a friendly label: prefer the browser-provided label,
                // fall back to "Kamera N"
                const label     = cam.label || `Kamera ${i + 1}`;
                opt.textContent = label;
                cameraSelect.appendChild(opt);

                // Prefer back/environment camera by default
                if (/back|rear|environment/i.test(label) && i !== 0) {
                    cameraSelect.value = cam.id;
                }
            });

            const count = cams.length;
            cameraCountLabel.textContent = `${count} kamera terdeteksi`;
        }

        // ── STEP 1: Detect cameras ────────────────────────────────────────
        btnDetect.addEventListener('click', function () {
            hideAlert();
            btnDetect.disabled = true;
            btnDetect.textContent = 'Mendeteksi...';

            Html5Qrcode.getCameras()
                .then(devices => {
                    btnDetect.disabled = false;
                    btnDetect.innerHTML = `
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg> Deteksi Kamera`;

                    if (!devices || devices.length === 0) {
                        showAlert('Tidak ada kamera yang terdeteksi. Pastikan kamera terhubung dan izin kamera telah diberikan di browser.', 'error');
                        return;
                    }

                    detectedCams = devices;
                    populateCameraDropdown(devices);
                    goTo('select');
                })
                .catch(err => {
                    btnDetect.disabled = false;
                    btnDetect.innerHTML = `
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg> Deteksi Kamera`;

                    let msg = 'Gagal mendeteksi kamera: ' + (err.message || err);
                    if (err && err.name === 'NotAllowedError') {
                        msg = 'Akses kamera ditolak. Silakan izinkan akses kamera di ikon kunci/gembok pada address bar browser Anda, lalu muat ulang halaman.';
                    }
                    showAlert(msg, 'error');
                });
        });

        // ── STEP 2: Back to idle ─────────────────────────────────────────
        btnBack.addEventListener('click', function () {
            hideAlert();
            goTo('idle');
        });

        // ── STEP 3: Start scanning with selected camera ──────────────────
        btnStart.addEventListener('click', function () {
            const selectedId = cameraSelect.value;
            const selectedLabel = cameraSelect.options[cameraSelect.selectedIndex]?.textContent || '';

            hideAlert();
            btnStart.disabled = true;
            btnStart.textContent = 'Memulai...';

            html5QrCode.start(
                selectedId,
                { fps: 10, qrbox: { width: 250, height: 250 } },
                (decodedText) => { validateToken(decodedText); },
                () => { /* ignore frame errors */ }
            ).then(() => {
                isScanning = true;
                activeCameraLabel.textContent = selectedLabel;
                btnStart.disabled = false;
                btnStart.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg> Mulai Scan`;
                goTo('scanning');
            }).catch(err => {
                btnStart.disabled = false;
                btnStart.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg> Mulai Scan`;

                let msg = 'Gagal mengaktifkan kamera yang dipilih.';
                if (err && err.name === 'NotAllowedError') {
                    msg = 'Akses kamera ditolak oleh browser.';
                } else if (err && err.name === 'NotReadableError') {
                    msg = 'Kamera sedang digunakan oleh aplikasi lain.';
                }
                showAlert(msg, 'error');
            });
        });

        // ── Stop scanning ─────────────────────────────────────────────────
        btnStop.addEventListener('click', function () {
            stopScanner().then(() => {
                hideAlert();
                goTo('idle');
            }).catch(() => goTo('idle'));
        });

        // ── Switch camera → back to select without re-detecting ──────────
        btnSwitch.addEventListener('click', function () {
            stopScanner().then(() => {
                if (detectedCams.length > 0) {
                    goTo('select');
                } else {
                    goTo('idle');
                }
            });
        });

        // ── Validate token via API ────────────────────────────────────────
        function validateToken(token) {
            if (!canValidate) return;
            canValidate = false;

            fetch('{{ route('admin.scan.validate') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ qr_token: token })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showAlert('✅ Berhasil! Mengalihkan halaman...', 'success');
                    setTimeout(() => { window.location.href = data.redirect_url; }, 1200);
                } else {
                    showAlert(data.message || 'Token tidak valid', 'error');
                    setTimeout(() => { canValidate = true; }, 3000);
                }
            })
            .catch(() => {
                showAlert('Terjadi kesalahan saat memvalidasi token.', 'error');
                setTimeout(() => { canValidate = true; }, 3000);
            });
        }

        // ── Manual token form ─────────────────────────────────────────────
        document.getElementById('manual-scan-form').addEventListener('submit', function (e) {
            e.preventDefault();
            const token = document.getElementById('manual_token').value.trim();
            if (token) validateToken(token);
        });
    });
    </script>
</x-app-layout>
