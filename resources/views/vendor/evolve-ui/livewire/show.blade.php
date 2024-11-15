<div>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ class_basename($modelClass) }} Details
                </h3>
                <div class="space-x-2">
                    <a
                        href="{{ route(Str::plural(Str::kebab(class_basename($modelClass))) . '.edit', $modelInstance->id) }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500"
                    >
                        Edit
                    </a>
                    <a
                        href="{{ route(Str::plural(Str::kebab(class_basename($modelClass))) . '.index') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Back to List
                    </a>
                </div>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    @foreach($modelInstance->getAttributes() as $key => $value)
                        <div class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">
                                {{ ucfirst(str_replace('_', ' ', $key)) }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                @if(is_array($value))
                                    <pre class="whitespace-pre-wrap">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                @elseif($value instanceof \Carbon\Carbon)
                                    {{ $value->format('Y-m-d H:i:s') }}
                                @else
                                    {{ $value }}
                                @endif
                            </dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            @foreach($modelInstance->getRelations() as $relation => $items)
                <div class="border-t border-gray-200">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            {{ ucfirst($relation) }}
                        </h3>
                    </div>
                    <div class="border-t border-gray-200">
                        @if($items instanceof \Illuminate\Database\Eloquent\Collection)
                            @foreach($items as $item)
                                <div class="border-b border-gray-200">
                                    <dl>
                                        @foreach($item->getAttributes() as $key => $value)
                                            <div class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                <dt class="text-sm font-medium text-gray-500">
                                                    {{ ucfirst(str_replace('_', ' ', $key)) }}
                                                </dt>
                                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                    @if(is_array($value))
                                                        <pre class="whitespace-pre-wrap">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                                    @elseif($value instanceof \Carbon\Carbon)
                                                        {{ $value->format('Y-m-d H:i:s') }}
                                                    @else
                                                        {{ $value }}
                                                    @endif
                                                </dd>
                                            </div>
                                        @endforeach
                                    </dl>
                                </div>
                            @endforeach
                        @elseif($items !== null)
                            <dl>
                                @foreach($items->getAttributes() as $key => $value)
                                    <div class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">
                                            {{ ucfirst(str_replace('_', ' ', $key)) }}
                                        </dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                            @if(is_array($value))
                                                <pre class="whitespace-pre-wrap">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                            @elseif($value instanceof \Carbon\Carbon)
                                                {{ $value->format('Y-m-d H:i:s') }}
                                            @else
                                                {{ $value }}
                                            @endif
                                        </dd>
                                    </div>
                                @endforeach
                            </dl>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
