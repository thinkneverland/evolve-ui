<?php

namespace Evolve\UI\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EvolveEditComponent extends Component
{
    public $modelClass;
    public $modelId;
    public $fields = [];

    public function mount($modelClass, $id)
    {
        $this->modelClass = $modelClass;
        $this->modelId = $id;
        $this->loadModel();
    }

    public function render()
    {
        return view('evolve-ui::livewire.edit', [
            'modelClass' => $this->modelClass,
        ]);
    }

    protected function loadModel()
    {
        $modelInstance = $this->modelClass::with($this->getRelations())->findOrFail($this->modelId);
        $this->fields = $this->extractFields($modelInstance);
    }

    protected function extractFields($modelInstance)
    {
        $fields = $modelInstance->toArray();
        $relations = array_diff(array_keys($this->modelClass::getAllRelations()), $this->modelClass::excludedRelations());

        foreach ($relations as $relation) {
            if (isset($fields[$relation])) {
                if (is_array($fields[$relation]) && array_key_exists(0, $fields[$relation])) {
                    // Multiple related models
                    foreach ($fields[$relation] as $index => $relatedModel) {
                        $relatedModelClass = get_class($modelInstance->$relation()->getRelated());
                        $fields[$relation][$index] = $this->extractFieldsRecursive($relatedModel, $relatedModelClass);
                    }
                } else {
                    // Single related model
                    $relatedModelClass = get_class($modelInstance->$relation()->getRelated());
                    $fields[$relation] = $this->extractFieldsRecursive($fields[$relation], $relatedModelClass);
                }
            }
        }

        return $fields;
    }

    protected function extractFieldsRecursive($data, $modelClass)
    {
        $fields = [];
        $fillable = (new $modelClass)->getFillable();
        $fieldsData = array_intersect_key($data, array_flip($fillable));

        foreach ($fieldsData as $key => $value) {
            $fields[$key] = $value;
        }

        $relations = array_diff(array_keys($modelClass::getAllRelations()), $modelClass::excludedRelations());
        foreach ($relations as $relation) {
            if (isset($data[$relation])) {
                $relatedModelClass = get_class((new $modelClass)->$relation()->getRelated());
                if (is_array($data[$relation]) && array_key_exists(0, $data[$relation])) {
                    // Multiple related models
                    foreach ($data[$relation] as $index => $relatedModel) {
                        $fields[$relation][$index] = $this->extractFieldsRecursive($relatedModel, $relatedModelClass);
                    }
                } else {
                    // Single related model
                    $fields[$relation] = $this->extractFieldsRecursive($data[$relation], $relatedModelClass);
                }
            }
        }

        return $fields;
    }

    protected function getRelations()
    {
        return array_diff(array_keys($this->modelClass::getAllRelations()), $this->modelClass::excludedRelations());
    }

    public function submit()
    {
        $modelInstance = $this->modelClass::findOrFail($this->modelId);

        $validatedData = $this->validate($this->modelClass::getValidationRules('update', $modelInstance));

        DB::beginTransaction();

        try {
            $this->saveModelWithRelations($this->modelClass, $validatedData['fields'], $modelInstance);

            DB::commit();

            session()->flash('message', 'Record updated successfully.');

            return redirect()->route(Str::plural(Str::kebab(class_basename($this->modelClass))) . '.index');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('error', 'Failed to update record: ' . $e->getMessage());
        }
    }

    protected function saveModelWithRelations($modelClass, $data, $existingModel = null)
    {
        // Similar logic as in the EvolveApiController's saveModelWithRelations method
        // Implement the method to handle both creating and updating nested relations
    }
}
