<?php

namespace Thinkneverland\Evolve\UI\Http\Livewire;

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
        ])->extends($this->getLayoutView());
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
                    foreach ($fields[$relation] as $index => $relatedModel) {
                        $relatedModelClass = get_class($modelInstance->$relation()->getRelated());
                        $fields[$relation][$index] = $this->extractFieldsRecursive($relatedModel, $relatedModelClass);
                    }
                } else {
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
                    foreach ($data[$relation] as $index => $relatedModel) {
                        $fields[$relation][$index] = $this->extractFieldsRecursive($relatedModel, $relatedModelClass);
                    }
                } else {
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
            $this->updateModelWithRelations($modelInstance, $validatedData['fields']);
            DB::commit();

            session()->flash('message', 'Record updated successfully.');
            return redirect()->route(Str::plural(Str::kebab(class_basename($this->modelClass))) . '.index');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('error', 'Failed to update record: ' . $e->getMessage());
        }
    }

    protected function updateModelWithRelations($model, $data)
    {
        $relations = array_diff(array_keys($this->modelClass::getAllRelations()), $this->modelClass::excludedRelations());

        $modelData = array_filter($data, function ($key) use ($relations) {
            return !in_array($key, $relations);
        }, ARRAY_FILTER_USE_KEY);

        $model->update($modelData);

        foreach ($relations as $relation) {
            if (isset($data[$relation])) {
                $relationData = $data[$relation];
                if (is_array($relationData) && array_key_exists(0, $relationData)) {
                    // Handle many relations
                    $model->$relation()->delete();
                    foreach ($relationData as $item) {
                        $relationClass = get_class($model->$relation()->getRelated());
                        $relationModel = $this->createModelWithRelations($relationClass, $item);
                        $model->$relation()->save($relationModel);
                    }
                } else {
                    // Handle single relation
                    $relationClass = get_class($model->$relation()->getRelated());
                    if ($model->$relation) {
                        $this->updateModelWithRelations($model->$relation, $relationData);
                    } else {
                        $relationModel = $this->createModelWithRelations($relationClass, $relationData);
                        $model->$relation()->save($relationModel);
                    }
                }
            }
        }

        return $model;
    }

    protected function createModelWithRelations($modelClass, $data)
    {
        $relations = array_diff(array_keys($modelClass::getAllRelations()), $modelClass::excludedRelations());

        $modelData = array_filter($data, function ($key) use ($relations) {
            return !in_array($key, $relations);
        }, ARRAY_FILTER_USE_KEY);

        $model = $modelClass::create($modelData);

        foreach ($relations as $relation) {
            if (isset($data[$relation])) {
                $relationData = $data[$relation];
                $relationClass = get_class($model->$relation()->getRelated());
                $relationModel = $this->createModelWithRelations($relationClass, $relationData);
                $model->$relation()->save($relationModel);
            }
        }

        return $model;
    }

    protected function getLayoutView()
    {
        return config('evolve-ui.views.layout', 'layouts.app');
    }
}
