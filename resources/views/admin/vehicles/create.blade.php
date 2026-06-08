<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Vehicle for') }} {{ $customer->customer_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.vehicles.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="customer_id" value="{{ $customer->customer_id }}">

                        <div class="mb-4 flex space-x-4">
                            <div class="w-1/3">
                                <label for="police_number" class="block text-gray-700 text-sm font-bold mb-2">Police Number</label>
                                <input type="text" name="police_number" id="police_number" value="{{ old('police_number') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required maxlength="20">
                                @error('police_number') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                            </div>
                            <div class="w-1/3">
                                <label for="engine_number" class="block text-gray-700 text-sm font-bold mb-2">Engine Number</label>
                                <input type="text" name="engine_number" id="engine_number" value="{{ old('engine_number') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" maxlength="50">
                                @error('engine_number') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                            </div>
                            <div class="w-1/3">
                                <label for="chassis_number" class="block text-gray-700 text-sm font-bold mb-2">Chassis Number</label>
                                <input type="text" name="chassis_number" id="chassis_number" value="{{ old('chassis_number') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" maxlength="50">
                                @error('chassis_number') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mb-4 flex space-x-4">
                            <div class="w-1/2">
                                <label for="brand" class="block text-gray-700 text-sm font-bold mb-2">Brand</label>
                                <input type="text" name="brand" id="brand" value="{{ old('brand') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required maxlength="100">
                                @error('brand') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                            </div>
                            <div class="w-1/2">
                                <label for="model" class="block text-gray-700 text-sm font-bold mb-2">Model</label>
                                <input type="text" name="model" id="model" value="{{ old('model') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required maxlength="100">
                                @error('model') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mb-4 flex space-x-4">
                            <div class="w-1/3">
                                <label for="color" class="block text-gray-700 text-sm font-bold mb-2">Color</label>
                                <input type="text" name="color" id="color" value="{{ old('color') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" maxlength="50">
                                @error('color') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                            </div>
                            <div class="w-1/3">
                                <label for="purchase_date" class="block text-gray-700 text-sm font-bold mb-2">Purchase Date</label>
                                <input type="date" name="purchase_date" id="purchase_date" value="{{ old('purchase_date') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                @error('purchase_date') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                            </div>
                            <div class="w-1/3">
                                <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                                <select name="status" id="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Save
                            </button>
                            <a href="{{ route('admin.customers.show', $customer) }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
