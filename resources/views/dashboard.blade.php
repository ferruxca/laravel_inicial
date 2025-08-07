<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
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
            </ol>
        </nav>

        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative aspect-video overflow-hidden rounded-xl p-4 border border-neutral-200 dark:border-neutral-700">
                @role('admin')
                <p>Solo visible por el administrador</p>
                @endrole
                @role(['admin', 'editor'])
                <p>Solo visible por el administrador y el editor</p>
                @endrole
                @role(['admin', 'user'])
                <p>Solo visible por el administrador y el usuario</p>
                @endrole
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl p-4 border border-neutral-200 dark:border-neutral-700">
                @role('editor')
                <p>Solo visible por el editor</p>
                @endrole
                @role(['admin', 'editor'])
                <p>Solo visible por el administrador y el editor</p>
                @endrole
                @role(['editor', 'user'])
                <p>Solo visible por el editor y el usuario</p>
                @endrole
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl p-4 border border-neutral-200 dark:border-neutral-700">
                @role('user')
                <p>Solo visible por el usuario</p>
                @endrole
                @role(['editor', 'user'])
                <p>Solo visible por el editor y el usuario</p>
                @endrole
                @role(['admin', 'user'])
                <p>Solo visible por el administrador y el usuario</p>
                @endrole
            </div>
        </div>
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
            <h2 class="text-lg font-semibold">Categorias</h2>
            @can('categoria.index')
                <p>Puede ver</p>
            @endcan
            @can('categoria.create')
                <p>Puede crear</p>
            @endcan
            @can('categoria.edit')
                <p>Puede editar</p>
            @endcan
            @can('categoria.destroy')
                <p>Puede eliminar</p>
            @endcan
        </div>
    </div>
</x-layouts.app>
