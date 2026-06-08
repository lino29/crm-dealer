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

                    <div id="reader" width="600px" class="w-full max-w-lg mb-4"></div>

                    <div class="w-full max-w-lg mb-4">
                        <p class="text-center font-semibold mb-2">Or enter token manually:</p>
                        <form id="manual-scan-form" class="flex">
                            <input type="text" id="manual_token" class="flex-1 shadow appearance-none border rounded-l py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter QR Token">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-r focus:outline-none focus:shadow-outline">
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
            let isScanning = true;

            function showAlert(message, type) {
                alertContainer.classList.remove('hidden');
                alertMessage.textContent = message;
                alertMessage.className = `p-4 rounded text-white font-bold text-center ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
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
                        showAlert('Success! Redirecting...', 'success');
                        setTimeout(() => {
                            window.location.href = data.redirect_url;
                        }, 1000);
                    } else {
                        showAlert(data.message || 'Invalid Token', 'error');
                        setTimeout(() => { isScanning = true; }, 3000);
                    }
                })
                .catch(error => {
                    showAlert('An error occurred during validation.', 'error');
                    setTimeout(() => { isScanning = true; }, 3000);
                });
            }

            Html5Qrcode.getCameras().then(devices => {
                if (devices && devices.length) {
                    var cameraId = devices[0].id;
                    html5QrCode.start(
                        cameraId, 
                        {
                            fps: 10,
                            qrbox: {width: 250, height: 250}
                        },
                        (decodedText, decodedResult) => {
                            validateToken(decodedText);
                        },
                        (errorMessage) => {
                            // parse error, ignore
                        })
                    .catch((err) => {
                        console.log('Error starting scanner', err);
                    });
                }
            }).catch(err => {
                console.log('No cameras found', err);
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
