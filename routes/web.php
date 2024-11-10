<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Evolve\Core\Contracts\EvolveModelInterface;
use Illuminate\Support\Facades\File;

$models = [];
$modelPath = app_path('Models');

foreach (File::allFiles($modelPath) as $file) {
    $namespace = app()->getNamespace() . 'Models\\';
    $class = $namespace . str_replace(['/', '.php'], ['\\', ''], $file->getRelativePathname());

    if (class_exists($class) && in_array(EvolveModelInterface::class, class_implements($class))) {
        if ($class::shouldEvolve()) {
            $models[] = $class;
        }
    }
}

foreach ($models as $modelClass) {
    $modelSlug = Str::plural(Str::kebab(class_basename($modelClass)));

    Route::get("evolve/{$modelSlug}", \Evolve\UI\Http\Livewire\EvolveIndexComponent::class)
        ->name("{$modelSlug}.index")
        ->defaults('modelClass', $modelClass);

    Route::get("evolve/{$modelSlug}/create", \Evolve\UI\Http\Livewire\EvolveCreateComponent::class)
        ->name("{$modelSlug}.create")
        ->defaults('modelClass', $modelClass);

    Route::get("evolve/{$modelSlug}/{id}/edit", \Evolve\UI\Http\Livewire\EvolveEditComponent::class)
        ->name("{$modelSlug}.edit")
        ->defaults('modelClass', $modelClass);

    Route::get("evolve/{$modelSlug}/{id}", \Evolve\UI\Http\Livewire\EvolveShowComponent::class)
        ->name("{$modelSlug}.show")
        ->defaults('modelClass', $modelClass);
}
