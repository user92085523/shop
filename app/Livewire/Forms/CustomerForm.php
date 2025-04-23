<?php

namespace App\Livewire\Forms;

use Exception;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CustomerForm extends Form
{
    public $inputs = [
        'name' => null,
        'phoneNumber' => null,
    ];
    public $msgs = [
        'name' => null,
        'phoneNumber' => null,
    ];

    public function rules()
    {
        return [
            'inputs.name' => 'required|min:4|max:45',
            'inputs.phoneNumber' => 'required|regex:/^\d{2,4}-\d{3,4}-\d{3,4}$/',
        ];
    }

    public function resetInputs()
    {
        $this->name = null;
        $this->phone_number = null;
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

    private function getCustomValidationMsg_phoneNumber($inputs, $unused, $msg_valid)
    {
        $phone_number = $inputs['phoneNumber'];

        if ($phone_number === '' || $phone_number === null) {
            return '*この項目は必須です*';
        }

        if (! preg_match('/^\d{2,4}-\d{2,4}-\d{3,4}$/', $phone_number)) {
            return '*NG(ハイフンも含める必要があります)*';
        }

        return $msg_valid;
    }
}
