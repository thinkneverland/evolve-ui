<div>
    <div class="flex items-center justify-between mb-4">
        <input
            type="text"
            wire:model.debounce.300ms="search"
            placeholder="Search..."
            class="border rounded px-4 py-2 w-1/3"
        >
        <select wire:model="perPage" class="border rounded px-4 py-2">
            <option value="10">10 per page</option>
            <option value="25">25 per page</option>
            <option value="50">50 per page</option>
            <option value="100">100 per page</option>
        </select>
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

    <div class="overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead>
            <tr>
                @foreach($getColumns() as $column)
                    <th
                        wire:click="sortBy('{{ $column }}')"
                        class="cursor-pointer px-4 py-2 bg-gray-100"
                    >
                        {{ Str::title(str_replace('_', ' ', $column)) }}
                        @if($sortField === $column)
                            <span class="ml-1">
                                @if($sortDirection === 'asc')
                                    ▲
                                @else
                                    ▼
                                @endif
                            </span>
                        @endif
                    </th>
                @endforeach
                <th class="px-4 py-2 bg-gray-100">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($items as $item)
                <tr>
                    @foreach($getColumns() as $column)
                        <td class="border px-4 py-2">{{ $item->$column }}</td>
                    @endforeach
                    <td class="border px-4 py-2 space-x-2">
                        <a
                            href="{{ route(Str::plural(Str::kebab(class_basename($modelClass))) . '.show', $item->id) }}"
                            class="inline-block bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded"
                        >
                            View
                        </a>
                        <a
                            href="{{ route(Str::plural(Str::kebab(class_basename($modelClass))) . '.edit', $item->id) }}"
                            class="inline-block bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded"
                        >
                            Edit
                        </a>
                        <button
                            wire:click="confirmDelete({{ $item->id }})"
                            class="inline-block bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded"
                        >
                            Delete
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $items->links() }}
    </div>

    @if($showDeleteConfirmation)
        <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900" id="modal-title">
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
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Delete
                        </button>
                        <button
                            wire:click="$set('showDeleteConfirmation', false)"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
