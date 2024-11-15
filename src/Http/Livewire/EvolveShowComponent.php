<?php

namespace Thinkneverland\Evolve\UI\Http\Livewire;

use Livewire\Component;

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
        ])->extends($this->getLayoutView());
    }

    protected function loadModel()
    {
        $this->modelInstance = $this->modelClass::with($this->getRelations())->findOrFail($this->modelId);
    }

    protected function getRelations()
    {
        return array_diff(array_keys($this->modelClass::getAllRelations()), $this->modelClass::excludedRelations());
    }

    protected function getLayoutView()
    {
        return config('evolve-ui.views.layout', 'layouts.app');
    }
}
