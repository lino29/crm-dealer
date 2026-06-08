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
                    <div class="w-96 h-56 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-2xl overflow-hidden relative text-white flex">
                        
                        <div class="flex-1 p-6 flex flex-col justify-between z-10">
                            <div>
                                <h2 class="text-2xl font-bold uppercase tracking-wider">{{ config('app.name', 'PT. Trijaya Motor') }}</h2>
                                <p class="text-sm opacity-80 mt-1">CRM Member Card</p>
                            </div>
                            
                            <div>
                                <p class="text-lg font-semibold tracking-widest">{{ $memberCard->member_code }}</p>
                                <p class="text-md uppercase mt-1">{{ $customer->customer_name }}</p>
                            </div>
                        </div>

                        <div class="w-1/3 bg-white flex flex-col justify-center items-center p-2 z-10">
                            <!-- QR Code generation -->
                            <div class="bg-white p-1 rounded">
                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(100)->generate($memberCard->qr_token) !!}
                            </div>
                            <p class="text-black text-xs mt-2 text-center font-bold">Scan Me</p>
                        </div>
                        
                        <!-- Decorative shapes -->
                        <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-white opacity-10"></div>
                        <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-24 h-24 rounded-full bg-black opacity-10"></div>
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
