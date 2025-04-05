<?php

namespace App\Livewire\Admin\User;

use App\Enums\Models\User\Role;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Symfony\Component\Translation\Dumper\DumperInterface;

class Index extends Component
{
    public array $query = [
        'model' => 'user',
        'prev_model' => '',
        'column' => '',
        'prev_column' => '',
        // 'method' => '',
        'operator' => '',
        'value' => '',
        'child_num' => 0,
    ];
    private bool $model_changed = false;
    private bool $column_changed = false;
    public array $models_info = [];
    public ?Collection $fetched_models = null;

    public function __construct()
    {
        $this->models_info[User::getModelNameLowerCase()] = User::getSearchInfo();
        $this->models_info[Employee::getModelNameLowerCase()] = Employee::getSearchInfo();
    }

    public function render()
    {
        $this->model_changed = $this->hasTargetModelChanged($this->query);
        $this->column_changed = $this->hasTargetColumnChanged($this->query);

        $this->resetFormData($this->model_changed, $this->column_changed, $this->query, $this->models_info);
        $this->update($this->query);

        return view('livewire.admin.user.index');
    }

    public function search()
    {
    }


    private function update(&$query)
    {
        if ($query['column']) {
            if ($this->column_changed || $this->model_changed) {
                $query['child_num'] = 0;
            }
            $child = $this->models_info[$query['model']][$query['column']]['childs'][$query['child_num']];
            $query['operator'] = $child['operator'];
            if ($child['value'] !== null) {
                $query['value'] = $child['value'];
            }
        }
        $query['prev_model'] = $query['model'];
        $query['prev_column'] = $query['column'];
    }

    private function hasTargetModelChanged($query)
    {
        return $query['model'] !== $query['prev_model'] ? true : false;
    }

    private function hasTargetColumnChanged($query)
    {
        return $query['column'] !== $query['prev_column'] ? true : false;
    }

    private function resetFormData($model_changed, $column_changed, &$query, $models_info)
    {
        if ($query['column'] === '') {
            $this->resetQuery($query);
            return;
        }

        if ($model_changed) {
            if (! array_key_exists($query['column'], $models_info[$query['model']])) {
                $query['column'] = '';
                $this->resetQuery($query);
            }
        }

        if ($column_changed) {
            $this->resetQuery($query);
        }


        // if ($column_changed) {
        //     if(! in_array($query['operator'], array_map(fn($child) => $child['operator'], $models_info[$query['model']][$query['column']]['child']))) {
        //         $query['operator'] = '';
        //     }
        // }

        // if ($query['operator'] === '') {
        //     $query['operator'] = $models_info[$query['model']][$query['column']]['childs'][0]['operator'];
        // }
    }

    private function resetQuery(&$query)
    {
        $query['operator'] = '';
        $query['keyword'] = '';
        $query['value'] = '';
        $query['child_num'] = 0;
    }
}
