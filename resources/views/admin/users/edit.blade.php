<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit User') }}</h2></x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf @method('PUT')
                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-1">Name *</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded border-gray-300" required>
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-1">Username *</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" class="w-full rounded border-gray-300" required>
                        @error('username')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded border-gray-300">
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-1">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded border-gray-300">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-1">Role *</label>
                        <select name="role_id" class="w-full rounded border-gray-300" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->role_id }}" {{ $user->role_id == $role->role_id ? 'selected' : '' }}>{{ ucfirst($role->role_name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-1">Status *</label>
                        <select name="status" class="w-full rounded border-gray-300" required>
                            <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $user->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-1">New Password <span class="text-gray-400 font-normal">(leave blank to keep current)</span></label>
                        <input type="password" name="password" class="w-full rounded border-gray-300">
                        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-bold mb-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="w-full rounded border-gray-300">
                    </div>
                    <div class="flex justify-end">
                        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 mr-2 bg-gray-200 rounded">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
