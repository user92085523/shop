<?php

namespace App\Livewire\Forms;

use Exception;
use Livewire\Attributes\Validate;
use Livewire\Form;

class AdminForm extends Form
{
    public $inputs = [
        'name' => null,
    ];
    public $msgs = [
        'name' => null,
    ];

    public function rules()
    {
        return [
            'inputs.name' => 'required|min:4|max:45',
        ];
    }

    public function resetInputs()
    {
        $this->name = null;
    }

    public function setCustomValidationMsg($msg_valid)
    {
        foreach ($this->msgs as $key => $value) {
            $method_name = 'getCustomValidationMsg_' . $key;
            if (method_exists($this, $method_name)) {
                $this->msgs[$key] = $this->{$method_name}($this->inputs, $this->msgs, $msg_valid);
                continue;
            }

            dump("method={$method_name} does not exist.");
            // throw new Exception("method={$method_name} does not exist.", 1);
        }
    }

    private function getCustomValidationMsg_name($inputs, $unused, $msg_valid)
    {
        $name = $inputs['name'];

        if ($name === '' || $name === null) {
            return '*この項目は必須です*';
        }

        if (strlen($name) < 4) {
            return '*最小2文字(全角のみ場合)*';
        }

        if (strlen($name) > 45) {
            return '*最大15文字(全角のみ場合)*';
        }

        return $msg_valid;
    }
}
