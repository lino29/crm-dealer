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
                    <div class="w-[384px] h-[224px] rounded-xl shadow-2xl overflow-hidden relative flex text-gray-800"
                         style="background-image: url('{{ asset('images/template_kosong.png') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat;">
                        
                        <div class="flex-1 p-6 flex flex-col justify-between z-10">
                            <div>
                                <h2 class="text-2xl font-bold uppercase tracking-wider text-gray-900">{{ config('app.name', 'PT. Trijaya Motor') }}</h2>
                                <p class="text-sm text-gray-600 mt-1">CRM Member Card</p>
                            </div>
                            
                            <div>
                                <p class="text-lg font-bold tracking-widest text-indigo-900">{{ $memberCard->member_code }}</p>
                                <p class="text-md font-semibold uppercase mt-1 text-gray-800">{{ $customer->customer_name }}</p>
                            </div>
                        </div>

                        <div class="w-1/3 bg-transparent flex flex-col justify-center items-center p-2 z-10">
                            <!-- QR Code generation -->
                            <div class="bg-white p-2 rounded-lg shadow-md border border-gray-100">
                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(100)->generate($memberCard->qr_token) !!}
                            </div>
                            <p class="text-gray-900 text-xs mt-2 text-center font-bold">Scan Me</p>
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
