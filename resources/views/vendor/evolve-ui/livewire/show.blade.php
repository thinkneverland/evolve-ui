<div>
    <h1>{{ class_basename($modelClass) }} Details</h1>

    <ul>
        @foreach($modelInstance->getAttributes() as $key => $value)
            <li><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}</li>
        @endforeach
    </ul>

    @foreach($modelInstance->getRelations() as $relation => $items)
        <h3>{{ ucfirst($relation) }}</h3>
        @if($items instanceof \Illuminate\Database\Eloquent\Collection)
            @foreach($items as $item)
                <ul>
                    @foreach($item->getAttributes() as $key => $value)
                        <li><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}</li>
                    @endforeach
                </ul>
            @endforeach
        @else
            <ul>
                @foreach($items->getAttributes() as $key => $value)
                    <li><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}</li>
                @endforeach
            </ul>
        @endif
    @endforeach
</div>
