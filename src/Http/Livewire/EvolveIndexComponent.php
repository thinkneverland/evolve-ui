<?php

namespace Evolve\UI\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class EvolveIndexComponent extends Component
{
    use WithPagination;

    public $modelClass;
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';

    public $showDeleteConfirmation = false;
    public $deleteId;

    protected $queryString = ['search', 'sortField', 'sortDirection'];

    public function mount($modelClass)
    {
        $this->modelClass = $modelClass;
    }

    public function render()
    {
        $query = $this->modelClass::query();

        if ($this->search) {
            $query->where(function ($q) {
                foreach ($this->getSearchableFields() as $field) {
                    $q->orWhere($field, 'like', '%' . $this->search . '%');
                }
            });
        }

        if ($this->sortField) {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        $items = $query->paginate(10);

        return view('evolve-ui::livewire.index', [
            'items' => $items,
            'modelClass' => $this->modelClass,
        ]);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    protected function getSearchableFields()
    {
        $model = new $this->modelClass;
        $columns = \Schema::getColumnListing($model->getTable());

        // Exclude any fields specified in the model
        $fields = array_diff($columns, $this->modelClass::excludedFields());

        return $fields;
    }

    protected function getColumns()
    {
        // You can customize the columns to display here
        return $this->getSearchableFields();
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteConfirmation = true;
    }

    public function delete()
    {
        $modelInstance = $this->modelClass::findOrFail($this->deleteId);
        $modelInstance->delete();

        $this->showDeleteConfirmation = false;
        $this->deleteId = null;

        session()->flash('message', 'Record deleted successfully.');
    }
}
