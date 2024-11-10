<div>
    <form wire:submit.prevent="submit">
        @foreach($fields as $key => $value)
            @if(is_array($value))
                <h3>{{ ucfirst($key) }}</h3>
                @include('evolve-ui::livewire.partials.fields', ['fields' => $fields[$key], 'parentKey' => $key])
            @else
                <div>
                    <label>{{ ucfirst(str_replace('_', ' ', $key)) }}</label>
                    <input type="text" wire:model="fields.{{ $key }}">
                    @error('fields.' . $key) <span class="error">{{ $message }}</span> @enderror
                </div>
            @endif
        @endforeach

        <button type="submit">Submit</button>
    </form>
</div>
