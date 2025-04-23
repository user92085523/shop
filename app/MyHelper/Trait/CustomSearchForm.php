<?php

namespace App\MyHelper\Trait;

use App\MyHelper\Class\QueryBuilder;
use Exception;
use Illuminate\Database\Eloquent\Collection;

trait CustomSearchForm
{
    public $csf_max_search_form_cnt = null;
    public $csf_target_model_name = [
        'cur' => '',
        'prev' => '',
    ];
    public $csf_target_model_info = [
        'name' => '',
        'path' => '',
        'class' => '',
    ];
    public $csf_search_forms = [];
    public $csf_prev_search_forms = [];

    public ?Collection $csf_records = null;
    public $csf_models_obj = null;

    public function csf_Search()
    {
        $query_ingredients = $this->csf_GetQueryIngredients(
            $this->csf_target_model_info,
            $this->csf_search_forms
        );
        $query = QueryBuilder::BuildQuery($query_ingredients);

        $this->csf_records = $query->get();
    }

    private function csf_GetQueryIngredients($target_model_info, &$search_forms)
    {
        $query_ingredients = [];
        $query_ingredients['class'] = $target_model_info['class'];

        for ($i = 0; $i < count($search_forms); $i++) {
            $query_ingredients['inputs'][$i] = $this->csf_CollectInputs($search_forms[$i]);
        }

        return $query_ingredients;
    }

    private function csf_CollectInputs(&$search_form)
    {
        $query_ingredient = [];

        foreach ($search_form as $row) {
            foreach ($row as $option) {
                $buff = $option['objs'][$option['obj_idx']];

                if (! isset($buff['flag'])) continue;

                $flag = $buff['flag'];

                if ($flag === 0b0000) {
                    if (isset($buff['data']['column']) || isset($buff['data']['method']) || isset($buff['data']['operator']) || isset($buff['data']['value'])) {
                        throw new Exception("{$option['html_tag']}/{$option['raw_path']} flag=$flag, ['data'] shoud be empty.", 1);
                    }
                }

                if (($flag & 0b1000) === 0b1000) {
                    $key = 'column';
                    $query_ingredient[$key] = $buff['data'][$key] ?? '';
                }
                if (($flag & 0b0100) === 0b0100) {
                    $key = 'method';
                    $query_ingredient[$key][] = $buff['data'][$key] ?? '';
                }
                if (($flag & 0b0010) === 0b0010) {
                    $key = 'operator';
                    $query_ingredient[$key][] = $buff['data'][$key] ?? '';
                }
                if (($flag & 0b0001) === 0b0001) {
                    $key = 'value';
                    $query_ingredient[$key][] = $buff['data'][$key] ?? '';
                }
            }
        }

        if (! isset($query_ingredient['column'])) {
            throw new Exception("start_path['label']={$search_form[0][0]['objs'][$search_form[0][0]['obj_idx']]['label']} | ['column'] not defined.", 1);
        }

        foreach (['method', 'operator', 'value'] as $idx => $key) {
            $query_ingredient[$key] ?? throw new Exception("start_path['label']={$search_form[0][0]['objs'][$search_form[0][0]['obj_idx']]['label']} | ['$key'] not defined.", 1);
            
            if ($idx === 0) {
                $cnt = count($query_ingredient[$key]);
                continue;
            }

            if ($cnt !== count($query_ingredient[$key])) {
                throw new Exception("['method'] cnt = " . count($query_ingredient['method']) . " ['operator'] cnt = " . count($query_ingredient['operator']) . " ['value'] cnt = " . count($query_ingredient['value']) . " | elements cnt does not match.",1);   
            }
        }

        return $query_ingredient;
    }

    public function csf_AddSearchForm()
    {
        if (count($this->csf_search_forms) >= $this->csf_max_search_form_cnt) return;
        $this->csf_CreateTree($this->csf_search_forms[]);
        $this->csf_CreateTree($this->csf_prev_search_forms[]);
    }

    public function csf_DeleteSearchForm($idx)
    {
        if ($idx <= 0) return;
        unset($this->csf_search_forms[$idx]);
        unset($this->csf_prev_search_forms[$idx]);
        $this->csf_search_forms = array_values($this->csf_search_forms);
        $this->csf_prev_search_forms = array_values($this->csf_prev_search_forms);
    }

    private function csf_Init($model_names, $max_search_form_cnt=4)
    {
        $init_model_name = $model_names[0];

        $this->csf_target_model_name = [
            'cur' => $init_model_name,
            'prev' => $init_model_name,
        ];

        foreach ($model_names as $name) {
            $import_class = 'App\Models\\' . $name;
            $this->csf_models_obj[$name] = $import_class::getSearchFormOption();
        }

        $this->csf_target_model_info['name'] = $init_model_name;
        $this->csf_target_model_info['path'] = $this->csf_models_obj[$init_model_name];
        $this->csf_target_model_info['class'] = $this->csf_models_obj[$init_model_name]['base']['class'];

        $this->csf_max_search_form_cnt = $max_search_form_cnt;

        $this->csf_AddSearchForm();
    }

    private function csf_Update()
    {
        $has_model_changed = $this->csf_HasModelChanged($this->csf_target_model_name);

        $this->csf_UpdateSearchForms(
            $has_model_changed,
            $this->csf_target_model_name,
            $this->csf_target_model_info,
            $this->csf_models_obj,
            $this->csf_records,
            $this->csf_search_forms,
            $this->csf_prev_search_forms
        );

        $this->csf_target_model_name['prev'] = $this->csf_target_model_name['cur'];
        $this->csf_prev_search_forms = $this->csf_search_forms;
    }

    private function csf_UpdateSearchForms(
        $has_model_changed,
        $model_name,
        &$model_info,
        $models_obj,
        &$records,
        &$search_forms,
        &$prev_search_forms)
    {
        if ($has_model_changed) {
            $this->csf_UpdateModelInfo(
                $model_name['cur'],
                $model_info,
                $models_obj
            );
            $this->csf_UpdateSearchForms_ChangedModel($search_forms);
            $records = null;
        } else {
            $this->csf_UpdateSearchForms_UnchangedModel(
                $search_forms,
                $prev_search_forms
            );
        }
    }

    private function csf_UpdateSearchForms_ChangedModel(&$search_forms)
    {
        $this->csf_UnsetAllSearchForm($search_forms);
        $this->csf_CreateTree($search_forms[]);
    }

    private function csf_UpdateSearchForms_UnchangedModel(&$search_forms, &$prev_search_forms)
    {
        if (! $diff = $this->csf_FindDiff($search_forms, $prev_search_forms)) return;

        $search_form = &$search_forms[$diff['form_num']];
        $x = $diff['x'];
        $y = $diff['y'];

        $this->csf_UnsetSearchForm($search_form, $x, $y);

        if (! $directives = $search_form[$x][$y]['objs'][$search_form[$x][$y]['obj_idx']]['next'] ?? null) return;

        $this->csf_CreateTree($search_form, $directives, $x + 1);
    }

    private function csf_CreateTree(&$search_form, $directives=['select/columns'], $x=0)
    {
        $default_idx = '0';
        //Model側定義で無限ループになってしまった場合のため
        $cnt = 0;

        while ($directives && $cnt < 10) {
            $dir_cnt = count($directives);

            for ($y = 0; $y < $dir_cnt; $y++) {
                [$html_tag, $raw_path, $path_chain] = $this->csf_DirectiveToVars($directives[$y]);

                $objs = $this->csf_GetTargetPathObjs($this->csf_target_model_info['path'], $path_chain);

                $objs ?? throw new Exception("$directives[$y] does not exist.", 1);

                $search_form[$x][$y]['html_tag'] = $html_tag;
                $search_form[$x][$y]['raw_path'] = $raw_path;
                $search_form[$x][$y]['path_chain'] = $path_chain;
                $search_form[$x][$y]['obj_idx'] = $default_idx;
                $search_form[$x][$y]['objs'] = $objs;
            }

            $directives = $objs[$default_idx]['next'] ?? null;

            $x++;
            $cnt++;
        }
    }

    private function csf_HasModelChanged($model_name)
    {
        return $model_name['cur'] !== $model_name['prev'];
    }

    private function csf_UpdateModelInfo($cur_model_name, &$model_info, &$models_obj)
    {
        $model_info['name'] = $cur_model_name;
        $model_info['path'] = &$models_obj[$cur_model_name];
        $model_info['class'] = $models_obj[$cur_model_name]['base']['class'];
    }

    private function csf_UnsetAllSearchForm(&$search_forms)
    {
        $cnt = count($search_forms);

        for ($i = 0; $i < $cnt; $i++) { 
            unset($search_forms[$i]);
        }
        
        $search_forms = array_values($search_forms);
    }

    private function csf_UnsetSearchForm(&$search_form, $x, $y)
    {
        if ($y !== count($search_form[$x]) - 1) {
            return;
        }

        if ($x !== count($search_form) - 1) {
            $cnt_x = count($search_form);
            for ($i = $x + 1; $i < $cnt_x; $i++) { 
                unset($search_form[$i]);
            }
        }
    }

    private function csf_FindDiff(&$search_forms, &$prev_search_forms)
    {
        for ($i = 0; $i < count($search_forms); $i++) { 
            for ($j = 0; $j < count($search_forms[$i]); $j++) { 
                for ($k = 0; $k < count($search_forms[$i][$j]); $k++) { 
                    if ($search_forms[$i][$j][$k]['obj_idx'] !== $prev_search_forms[$i][$j][$k]['obj_idx']) {
                        return [
                            'form_num' => $i,
                            'x' => $j,
                            'y' => $k,
                        ];
                    }
                }
            }
        }

        return null;
    }

    private function csf_GetTargetPathObjs(&$base_path, $path_chain)
    {
        $buff = $base_path;

        foreach ($path_chain as $path) {
            $buff = &$buff[$path];
        }

        return $buff;
    }

    private function csf_DirectiveToVars($dir)
    {
        [$html_tag, $raw_path] = explode('/', $dir);
        $path_chain = explode('.', $raw_path);

        return [$html_tag, $raw_path, $path_chain];
    }
}