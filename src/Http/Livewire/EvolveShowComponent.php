<?php

namespace Evolve\UI\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class EvolveShowComponent extends Component
{
    public $modelClass;
    public $modelId;
    public $modelInstance;

    public function mount($modelClass, $id)
    {
        $this->modelClass = $modelClass;
        $this->modelId = $id;
        $this->loadModel();
    }

    public function render()
    {
        return view('evolve-ui::livewire.show', [
            'modelClass' => $this->modelClass,
            'modelInstance' => $this->modelInstance,
        ]);
    }

    protected function loadModel()
    {
        $this->modelInstance = $this->modelClass::with($this->getRelations())->findOrFail($this->modelId);
    }

    protected function getRelations()
    {
        return array_diff(array_keys($this->modelClass::getAllRelations()), $this->modelClass::excludedRelations());
    }
}
