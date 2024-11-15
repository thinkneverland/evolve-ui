<div>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Create {{ class_basename($modelClass) }}</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Create a new {{ Str::lower(class_basename($modelClass)) }} record.
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <form wire:submit.prevent="submit">
                    <div class="shadow sm:rounded-md sm:overflow-hidden">
                        <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                            @foreach($fields as $key => $value)
                                @if(is_array($value))
                                    <div class="border-t border-gray-200 pt-4">
                                        <h3 class="text-lg font-medium text-gray-900">{{ ucfirst($key) }}</h3>
                                        @include('evolve-ui::livewire.partials.fields', ['fields' => $fields[$key], 'parentKey' => $key])
                                    </div>
                                @else
                                    <div>
                                        <label for="{{ $key }}" class="block text-sm font-medium text-gray-700">
                                            {{ ucfirst(str_replace('_', ' ', $key)) }}
                                        </label>
                                        <div class="mt-1">
                                            <input
                                                type="text"
                                                id="{{ $key }}"
                                                wire:model="fields.{{ $key }}"
                                                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                            >
                                        </div>
                                        @error('fields.' . $key)
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button
                                type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Create
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
