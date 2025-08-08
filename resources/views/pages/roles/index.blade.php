<x-layouts.app :title="__('Roles Management')">
    <div class="container mx-auto px-4 py-8">
        <!-- Encabezado con breadcrumbs y título -->
        <div class="mb-6">
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                            </svg>
                            {{ __('Dashboard') }}
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Roles</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ __('Roles Management') }}
                </h1>
                
                @can('role.create')
                <a href="{{ route('roles.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    {{ __('New Role') }}
                </a>
                @endcan
            </div>
        </div>

        <!-- Tarjeta contenedora -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
            <!-- Header de la tarjeta -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                    {{ __('Roles List') }}
                </h2>
                <div class="relative">
                    <form method="GET" action="{{ route('roles.index') }}" class="relative">
                        <input type="text" 
                               name="search" 
                               value="{{ $search ?? '' }}"
                               placeholder="{{ __('Search permissions...') }}" 
                               class="pl-8 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white w-64"
                               x-data="{ search: '{{ $search ?? '' }}' }"
                               x-model="search"
                               x-on:input.debounce.500ms="$el.form.submit()">
                        <div class="absolute left-3 top-2.5 text-gray-400 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        @if($search)
                        <button type="button" 
                                class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                                onclick="document.querySelector('input[name=search]').value = ''; this.closest('form').submit();">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Matriz de Roles y Permisos -->
            <div class="overflow-x-auto">
                @if($roles->count() > 0)

                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sticky left-0 bg-gray-50 dark:bg-gray-700 z-10">
                                    {{ __('Permissions') }}
                                </th>
                                @foreach($roles as $role)
                                    <th scope="col" class="px-4 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider min-w-[120px]">
                                        <div class="flex flex-col items-center">
                                            @can('role.edit', $role)
                                            <a href="{{ route('roles.edit', $role) }}"> 
                                            <div class="flex-shrink-0 h-6 w-6 flex items-center justify-center rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 mb-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            </a>
                                            @endcan
                                            <span class="text-xs font-semibold">{{ $role->name }}</span>
                                            <span class="text-xs text-gray-400">{{ $role->permissions->count() }} permisos</span>
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @php
                                $allPermissions = $permissions->flatten();
                            @endphp
                            @forelse($allPermissions->groupBy('group') as $group => $groupPermissions)
                                <!-- Group Header -->
                                <tr class="bg-gray-100 dark:bg-gray-700">
                                    <td colspan="{{ $roles->count() + 1 }}" class="px-6 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                        {{ $group ?? __('General') }}
                                    </td>
                                </tr>
                                @foreach($groupPermissions as $permission)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <td class="px-6 py-2 whitespace-nowrap sticky left-0 bg-white dark:bg-gray-800 z-10">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $permission->name }}
                                            </div>
                                            @if($permission->description)
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $permission->description }}
                                                </div>
                                            @endif
                                        </td>
                                        @foreach($roles as $role)
                                            <td class="px-4 py-2 text-center">
                                                @can('role.edit')
                                                    <label class="inline-flex items-center cursor-pointer">
                                                        <input type="checkbox" 
                                                            @if($role->id == 1) disabled="disabled" @endif
                                                            class="permission-checkbox form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500 focus:ring-2 transition-colors"
                                                            data-role-id="{{ $role->id }}"
                                                            data-permission-id="{{ $permission->id }}"
                                                            {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                                        <span class="sr-only">{{ __('Toggle permission') }} {{ $permission->name }} {{ __('for role') }} {{ $role->name }}</span>
                                                    </label>
                                                @else
                                                    <div class="flex items-center justify-center">
                                                        @if($role->hasPermissionTo($permission->name))
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                            </svg>
                                                        @else
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        @endif
                                                    </div>
                                                @endcan
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="{{ $roles->count() + 1 }}" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                        <p>{{ __('No permissions found') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-12">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <p class="text-lg font-medium text-gray-900 dark:text-white mb-2">{{ __('No roles found') }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ __('Get started by creating your first role.') }}</p>
                        @can('role.create')
                            <a href="{{ route('roles.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                {{ __('Create Role') }}
                            </a>
                        @endcan
                    </div>
                @endif
            </div>

            <!-- Paginación -->
            @if($permissions->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $permissions->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- JavaScript para manejar los checkboxes -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Obtener el token CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                             document.querySelector('input[name="_token"]')?.value;

            // Agregar event listeners a todos los checkboxes
            document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const roleId = this.getAttribute('data-role-id');
                    const permissionId = this.getAttribute('data-permission-id');
                    const isChecked = this.checked;
                    
                    // Deshabilitar el checkbox mientras se procesa
                    this.disabled = true;
                    
                    // Realizar la petición AJAX
                    fetch(`/roles/${roleId}/toggle-permission`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            permission_id: permissionId
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Mostrar mensaje de éxito (opcional)
                            showNotification(data.message, 'success');
                            
                            // Actualizar el estado del checkbox basado en la respuesta
                            this.checked = data.hasPermission;
                        } else {
                            // Revertir el checkbox si hubo error
                            this.checked = !isChecked;
                            showNotification('Error al actualizar el permiso', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Revertir el checkbox en caso de error
                        this.checked = !isChecked;
                        showNotification('Error de conexión', 'error');
                    })
                    .finally(() => {
                        // Rehabilitar el checkbox
                        this.disabled = false;
                    });
                });
            });

            // Función para mostrar notificaciones
            function showNotification(message, type = 'info') {
                // Crear elemento de notificación
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 z-50 px-4 py-2 rounded-md text-white text-sm font-medium transition-all duration-300 transform translate-x-full opacity-0 ${
                    type === 'success' ? 'bg-green-500' : 
                    type === 'error' ? 'bg-red-500' : 'bg-blue-500'
                }`;
                notification.textContent = message;
                
                // Agregar al DOM
                document.body.appendChild(notification);
                
                // Animar entrada
                setTimeout(() => {
                    notification.classList.remove('translate-x-full', 'opacity-0');
                }, 100);
                
                // Animar salida y remover
                setTimeout(() => {
                    notification.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => {
                        document.body.removeChild(notification);
                    }, 300);
                }, 3000);
            }
        });
    </script>
</x-layouts.app>