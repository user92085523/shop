<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Exception;
use Livewire\Form;
use App\Enums\Models\User\Role;

class UserForm extends Form
{
    public $inputs = [
        'loginId' => null,
        'password' => null,
        'confirmation_password' => null,
        'role' => Role::Employee->value,
        'prev_role' => Role::Employee->value,
    ];
    public $msgs = [
        'loginId' => null,
        'password' => null,
        'confirmation_password' => null,
    ];

    protected function rules()
    {
        return [
            'inputs.loginId' => 'required|alphanum|min:4|max:16',
            'inputs.password' => 'required|alphanum|min:4|max:16',
            'inputs.confirmation_password' => 'required',
            'inputs.role' => 'required',
        ];
    }

    protected function messages()
    {
        return [
            'inputs.loginId.required' => '*:attributeは必須です*',
            'inputs.loginId.alphanum' => '*半角英数字のみ使用できます*',
            'inputs.loginId.min' => '*最低4文字です*',
            'inputs.loginId.max' => '*最大16文字です*',
            'inputs.password.required' => '*:attributeは必須です*',
            'inputs.password.alphanum' => '*半角英数字のみ使用できます*',
            'inputs.password.min' => '*最低4文字です*',
            'inputs.password.max' => '*最大16文字です*',
            'inputs.confirmation_password' => '*:attributeは必須です*'
        ];
    }

    protected function validationAttributes()
    {
        return [
            'inputs.loginId' => 'ログインID',
            'inputs.password' => 'パスワード',
            'inputs.confirmation_password' => '確認用パスワード',
        ];
    }

    public function hasRoleChanged()
    {
        return $this->inputs['role'] !== $this->inputs['prev_role'];
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

    private function getCustomValidationMsg_loginId($inputs, $unused, $msg_valid)
    {
        $loginId = $inputs['loginId'];

        if ($loginId === '' || $loginId === null) {
            return '*この項目は必須です*';
        }

        if (! ctype_alnum($loginId)) {
            return '*半角英数字のみ使用できます*';
        }

        if (strlen($loginId) < 4) {
            return '*最小4文字*';
        }

        if (strlen($loginId) > 16) {
            return '*最大16文字*';
        }

        if (User::loginIdExist($loginId)) {
            return '*IDは既に存在しています*';
        }

        return $msg_valid;
    }

    private function getCustomValidationMsg_password($inputs, $unused, $msg_valid)
    {
        $password = $inputs['password'];

        if ($password === '' || $password === null) {
            return '*この項目は必須です*';
        }

        if (! ctype_alnum($password)) {
            return '*半角英数字のみ使用できます*';
        }

        if (strlen($password) < 4) {
            return '*最小4文字*';
        }

        if (strlen($password) > 16) {
            return '*最大16文字*';
        }

        return $msg_valid;
    }

    private function getCustomValidationMsg_confirmation_password($inputs, $msgs, $msg_valid)
    {
        $c_password = $inputs['confirmation_password'];
        $password = $inputs['password'];
        $msg_password = $msgs['password'];

        if ($c_password === '' || $c_password === null) {
            return '*この項目は必須です*';
        }

        if ($msg_password !== $msg_valid) {
            return '*パスワードに問題があります*';
        }

        if ($c_password !== $password) {
            return '*パスワードと一致していません*';
        }

        return $msg_valid;
    }
}
