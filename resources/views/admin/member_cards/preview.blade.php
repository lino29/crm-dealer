<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Preview Member Card') }}: {{ $customer->customer_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex flex-col items-center">
                    
                    <!-- Card Design -->
                    <div class="w-[384px] h-[224px] rounded-xl shadow-2xl overflow-hidden relative"
                         style="background-image: url('{{ asset('images/template_kosong.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat;">
                        
                        <!-- QR Code inside the gray box at bottom-left -->
                        <div class="absolute flex items-center justify-center bg-white p-0.5 rounded shadow" 
                             style="left: 9.65%; top: 59.16%; width: 18.50%; height: 29.54%;">
                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(65)->margin(1)->generate($memberCard->qr_token) !!}
                        </div>

                        <!-- Member Code and Customer Name below the QR Code (White text on dark blue background) -->
                        <div class="absolute flex flex-col justify-end" 
                             style="left: 9.25%; top: 89.5%; width: 45%; height: 8%;">
                            <p class="text-[9px] font-bold tracking-wider text-white leading-none">{{ $memberCard->member_code }}</p>
                            <p class="text-[9px] font-bold uppercase text-white leading-none mt-0.5">{{ $customer->customer_name }}</p>
                        </div>
                    </div>

                    <div class="mt-8 flex space-x-4">
                        <a href="{{ route('admin.member_cards.print', $customer) }}" target="_blank" class="px-6 py-2 bg-indigo-600 text-white rounded shadow hover:bg-indigo-700 transition">Print PDF</a>
                        <a href="{{ route('admin.customers.show', $customer) }}" class="px-6 py-2 bg-gray-500 text-white rounded shadow hover:bg-gray-600 transition">Back to Customer</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
