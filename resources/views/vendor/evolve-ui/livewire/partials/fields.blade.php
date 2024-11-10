@foreach($fields as $key => $value)
    @if(is_array($value))
        <h4>{{ ucfirst($key) }}</h4>
        @include('evolve-ui::livewire.partials.fields', ['fields' => $fields[$key], 'parentKey' => $parentKey . '.' . $key])
    @else
        <div>
            <label>{{ ucfirst(str_replace('_', ' ', $key)) }}</label>
            <input type="text" wire:model="fields.{{ $parentKey }}.{{ $key }}">
            @error('fields.' . $parentKey . '.' . $key) <span class="error">{{ $message }}</span> @enderror
        </div>
    @endif
@endforeach
