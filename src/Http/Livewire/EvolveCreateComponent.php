<?php

namespace Evolve\UI\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class EvolveCreateComponent extends Component
{
    public $modelClass;
    public $fields = [];

    public function mount($modelClass)
    {
        $this->modelClass = $modelClass;
        $this->initializeFields();
    }

    public function render()
    {
        return view('evolve-ui::livewire.create', [
            'modelClass' => $this->modelClass,
        ]);
    }

    protected function initializeFields()
    {
        $this->fields = $this->generateFields($this->modelClass);
    }

    protected function generateFields($modelClass)
    {
        $fields = [];
        $model = new $modelClass;

        $columns = \Schema::getColumnListing($model->getTable());
        $fillable = $model->getFillable();
        $fieldsData = array_diff($fillable, $modelClass::excludedFields());

        foreach ($fieldsData as $field) {
            $fields[$field] = null;
        }

        $relations = array_diff(array_keys($modelClass::getAllRelations()), $modelClass::excludedRelations());
        foreach ($relations as $relation) {
            $relatedModelClass = get_class($model->$relation()->getRelated());
            $fields[$relation] = $this->generateFields($relatedModelClass);
        }

        return $fields;
    }

    public function submit()
    {
        $validatedData = $this->validate($this->modelClass::getValidationRules('create'));

        DB::beginTransaction();

        try {
            $this->createModelWithRelations($this->modelClass, $validatedData['fields']);

            DB::commit();

            session()->flash('message', 'Record created successfully.');

            return redirect()->route(Str::plural(Str::kebab(class_basename($this->modelClass))) . '.index');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('error', 'Failed to create record: ' . $e->getMessage());
        }
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

                // Associate the relation
                $model->$relation()->save($relationModel);
            }
        }

        return $model;
    }
}
