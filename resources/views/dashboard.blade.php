<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
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
