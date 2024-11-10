<div>
    <div class="flex items-center justify-between mb-4">
        <input
            type="text"
            wire:model.debounce.300ms="search"
            placeholder="Search..."
            class="border rounded px-4 py-2 w-1/3"
        >
        <a
            href="{{ route(Str::plural(Str::kebab(class_basename($modelClass))) . '.create') }}"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        >
            Create New
        </a>
    </div>

    @if(session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    <table class="table-auto w-full">
        <thead>
        <tr>
            @foreach($getColumns() as $column)
                <th
                    wire:click="sortBy('{{ $column }}')"
                    class="cursor-pointer px-4 py-2"
                >
                    {{ Str::title(str_replace('_', ' ', $column)) }}
                    @if($sortField === $column)
                        @if($sortDirection === 'asc')
                            ▲
                        @else
                            ▼
                        @endif
                    @endif
                </th>
            @endforeach
            <th class="px-4 py-2">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($items as $item)
            <tr>
                @foreach($getColumns() as $column)
                    <td class="border px-4 py-2">{{ $item->$column }}</td>
                @endforeach
                <td class="border px-4 py-2">
                    <a
                        href="{{ route(Str::plural(Str::kebab(class_basename($modelClass))) . '.show', $item->id) }}"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded"
                    >
                        View
                    </a>
                    <a
                        href="{{ route(Str::plural(Str::kebab(class_basename($modelClass))) . '.edit', $item->id) }}"
                        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded"
                    >
                        Edit
                    </a>
                    <button
                        wire:click="confirmDelete({{ $item->id }})"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded"
                    >
                        Delete
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $items->links() }}
    </div>

    <!-- Delete Confirmation Modal -->
    @if($showDeleteConfirmation)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:w-full sm:max-w-lg">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Confirm Delete
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Are you sure you want to delete this record? This action cannot be undone.
                            </p>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button
                            wire:click="delete"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600
                    text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2
                    focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Yes, Delete
                        </button>
                        <button
                            wire:click="$set('showDeleteConfirmation', false)"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2
                    bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2
                    focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
