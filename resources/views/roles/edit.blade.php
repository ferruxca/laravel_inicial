<x-layouts.app :title="__('Edit Role: ') . $role->name">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto">
            <!-- Encabezado -->
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                    {{ __('Edit Role: ') }} <span class="text-blue-600">{{ $role->name }}</span>
                </h1>
                <a href="{{ route('roles.index') }}" 
                   class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    {{ __('Back to roles') }}
                </a>
            </div>

            <!-- Formulario -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <form method="POST" action="{{ route('roles.update', $role) }}">
                    @csrf
                    @method('PUT')
                    
                    <!-- Card de información básica -->
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-medium text-gray-800 dark:text-white mb-4">
                            {{ __('Role Information') }}
                        </h2>
                        
                        <!-- Nombre -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('Role Name') }} *
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $role->name) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   required autofocus>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Card de permisos -->
                    <div class="p-6">
                        <h2 class="text-lg font-medium text-gray-800 dark:text-white mb-4">
                            {{ __('Current Permissions') }}
                        </h2>
                        
                        <div class="space-y-6">
                            @foreach($permissions as $group => $groupPermissions)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <h3 class="text-md font-medium text-gray-800 dark:text-gray-200 mb-3 capitalize">
                                    {{ $group }}
                                </h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                    @foreach($groupPermissions as $permission)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                                               @checked($role->hasPermissionTo($permission))>
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                            {{ $permission->name }}
                                        </span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Footer del formulario -->
                    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 flex justify-end gap-3">
                        <a href="{{ route('roles.index') }}"
                           class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit"
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('Update Role') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>