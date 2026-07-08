<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scan Member Card') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex flex-col items-center">
                    
                    <div id="alert-container" class="w-full mb-4 hidden">
                        <div id="alert-message" class="p-4 rounded text-white font-bold text-center"></div>
                    </div>

                    <div class="w-full max-w-lg mb-4">
                        <button id="toggle-camera-btn" class="w-full bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded focus:outline-none focus:shadow-outline flex items-center justify-center transition">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span id="toggle-btn-text">Aktifkan Kamera</span>
                        </button>
                    </div>

                    <div id="reader" width="600px" class="w-full max-w-lg mb-4 bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden flex items-center justify-center" style="min-height: 250px;">
                        <span id="camera-placeholder-text" class="text-gray-400">Kamera belum diaktifkan</span>
                    </div>

                    <div class="w-full max-w-lg mb-4 mt-6">
                        <p class="text-center font-semibold mb-2">Atau masukkan token secara manual:</p>
                        <form id="manual-scan-form" class="flex">
                            <input type="text" id="manual_token" class="flex-1 shadow appearance-none border border-gray-300 rounded-l py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Masukkan QR Token">
                            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-6 rounded-r focus:outline-none focus:shadow-outline">
                                Submit
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Include html5-qrcode -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const html5QrCode = new Html5Qrcode("reader");
            const alertContainer = document.getElementById('alert-container');
            const alertMessage = document.getElementById('alert-message');
            const toggleBtn = document.getElementById('toggle-camera-btn');
            const toggleBtnText = document.getElementById('toggle-btn-text');
            const cameraPlaceholderText = document.getElementById('camera-placeholder-text');
            
            let isScanning = true;
            let cameraActive = false;

            function showAlert(message, type) {
                alertContainer.classList.remove('hidden');
                alertMessage.textContent = message;
                alertMessage.className = `p-4 rounded text-white font-bold text-center shadow-sm ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
            }

            function validateToken(token) {
                if (!isScanning) return;
                isScanning = false;

                fetch('{{ route('admin.scan.validate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ qr_token: token })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('Berhasil! Mengalihkan halaman...', 'success');
                        setTimeout(() => {
                            window.location.href = data.redirect_url;
                        }, 1000);
                    } else {
                        showAlert(data.message || 'Token tidak valid', 'error');
                        setTimeout(() => { isScanning = true; }, 3000);
                    }
                })
                .catch(error => {
                    showAlert('Terjadi kesalahan saat memvalidasi token.', 'error');
                    setTimeout(() => { isScanning = true; }, 3000);
                });
            }

            toggleBtn.addEventListener('click', function() {
                if (cameraActive) {
                    // Stop camera
                    html5QrCode.stop().then(() => {
                        cameraActive = false;
                        toggleBtnText.textContent = 'Aktifkan Kamera';
                        toggleBtn.classList.replace('bg-red-500', 'bg-indigo-500');
                        toggleBtn.classList.replace('hover:bg-red-700', 'hover:bg-indigo-700');
                        cameraPlaceholderText.style.display = 'block';
                    }).catch(err => {
                        showAlert('Gagal menonaktifkan kamera: ' + err, 'error');
                    });
                } else {
                    // Start camera
                    Html5Qrcode.getCameras().then(devices => {
                        if (devices && devices.length) {
                            // Try to use environment (back) camera if available
                            html5QrCode.start(
                                { facingMode: "environment" }, 
                                {
                                    fps: 10,
                                    qrbox: {width: 250, height: 250}
                                },
                                (decodedText, decodedResult) => {
                                    validateToken(decodedText);
                                },
                                (errorMessage) => {
                                    // Ignore frame parsing errors
                                }
                            ).then(() => {
                                cameraActive = true;
                                toggleBtnText.textContent = 'Nonaktifkan Kamera';
                                toggleBtn.classList.replace('bg-indigo-500', 'bg-red-500');
                                toggleBtn.classList.replace('hover:bg-indigo-700', 'hover:bg-red-700');
                                cameraPlaceholderText.style.display = 'none';
                                alertContainer.classList.add('hidden'); // Clear any previous errors
                            }).catch((err) => {
                                let errorMsg = 'Gagal mengaktifkan kamera. Pastikan Anda telah memberikan izin kamera pada browser.';
                                if (err && err.name === 'NotAllowedError') {
                                    errorMsg = 'Akses kamera ditolak oleh browser. Silakan izinkan akses kamera di pengaturan browser Anda.';
                                } else if (err && err.name === 'NotFoundError') {
                                    errorMsg = 'Kamera tidak ditemukan atau sedang digunakan oleh aplikasi lain.';
                                }
                                showAlert(errorMsg, 'error');
                                console.log('Error starting scanner:', err);
                            });
                        } else {
                            showAlert('Kamera tidak ditemukan pada perangkat ini.', 'error');
                        }
                    }).catch(err => {
                        showAlert('Terjadi kesalahan saat mencari kamera: ' + err.message, 'error');
                        console.log('Error getting cameras:', err);
                    });
                }
            });

            document.getElementById('manual-scan-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const token = document.getElementById('manual_token').value;
                if(token) {
                    validateToken(token);
                }
            });
        });
    </script>
</x-app-layout>
