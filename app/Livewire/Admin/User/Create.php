<?php

namespace App\Livewire\Admin\User;

use App\Enums\Models\EmployeePosition\Name;
use App\Livewire\Forms\AdminForm;
use App\Livewire\Forms\CustomerForm;
use App\Livewire\Forms\EmployeeForm;
use App\Livewire\Forms\UserForm;
use App\Models\Admin;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\EmployeePosition;
use App\Models\User;
use DB;
use Exception;
use Livewire\Component;

class Create extends Component
{
    public $user_creation_form_obj = [];
    public $base_model_name = 'User';
    public $msg_valid = 'OK!';
    public UserForm $user_form;
    public EmployeeForm $employee_form;
    public CustomerForm $customer_form;
    public AdminForm $admin_form;

    public function mount()
    {
        $this->user_creation_form_obj[$this->base_model_name] = $this->getModelCreationFormObj($this->base_model_name);

        $this->user_creation_form_obj[$this->user_form->inputs['role']] = $this->getModelCreationFormObj($this->user_form->inputs['role']);
    }

    public function exception($e)
    {
    }

    public function createUser()
    {
        $creation_models = [$this->base_model_name, $this->user_form->inputs['role']];
        $validated = [];
        foreach ($creation_models as $unused => $model_name) {
            $property_name = $this->getFormPropertyName($model_name);

            $validated[$model_name] = $this->{$property_name}->validate();
        }

        $calling_method_name = 'createUserWith' . $model_name;
        $this->{$calling_method_name}($validated);
    }

    private function CreateUserWithEmployee($validated)
    {
        if (! in_array($validated['Employee']['inputs']['employee_position'], array_map(fn($name) => $name->value, Name::cases()))) {
            return redirect('/admin/user/create')->with('msg', '不正なデータが確認されました');
        }

        $validated['Employee']['inputs']['employee_position_id'] = EmployeePosition::where('name', $validated['Employee']['inputs']['employee_position'])->first()->id;

        try {
            DB::transaction(function () use ($validated) {
                $user = User::create($validated['User']['inputs']);
    
                Employee::create([...$validated['Employee']['inputs'], 'user_id' => $user->id]);

                return redirect("/admin/user/{$user->publicId}")->with('msg');
            });
        } catch (\Throwable $th) {
            return redirect('/admin/user/create')->with('msg', 'データベースへの登録に失敗しました');
        }
    }

    private function CreateUserWithCustomer($validated)
    {
        try {
            DB::transaction(function () use ($validated) {
                $user = User::create($validated['User']['inputs']);
                Customer::create([...$validated['Customer']['inputs'], 'user_id' => $user->id]);

                return redirect("/admin/user/{$user->publicId}");
            });
        } catch (\Throwable $th) {
            return redirect('/admin/user/create')->with('msg', 'データベースへの登録に失敗しました');
        }
    }

    private function CreateUserWithAdmin($validated)
    {
        try {
            DB::transaction(function () use ($validated) {
                $user = User::create($validated['User']['inputs']);
                Admin::create([...$validated['Admin']['inputs'], 'user_id' => $user->id]);

                return redirect("/admin/user/{$user->publicId}");
            });
        } catch (\Throwable $th) {
            return redirect('/admin/user/create')->with('msg', 'データベースへの登録に失敗しました');
        }
    }

    public function updateData()
    {
        if ($this->user_form->hasRoleChanged()) {
            $this->handleRoleChange(
                $this->user_creation_form_obj,
                $this->user_form,
                $this->employee_form,
                $this->customer_form,
                $this->admin_form,
            );
        }

        foreach ([$this->base_model_name, $this->user_form->inputs['role']] as $unused => $value) {
            $property_name = $this->getFormPropertyName($value);
            $method_name = 'setCustomValidationMsg';

            if (method_exists($this->{$property_name}, $method_name)) {
                $this->{$property_name}->$method_name($this->msg_valid);
                continue;
            }

            dump("{$property_name}->{$method_name} does not exist.");

            // throw new Exception("{$property_name}->{$method_name} does not exist.", 1);  
        }

        $this->user_form->inputs['prev_role'] = $this->user_form->inputs['role'];
    }
    
    private function handleRoleChange(
        &$user_creation_form_obj,
        &$user_form,
        &$employee_form,
        &$customer_form,
        &$admin_form,
    )
    {
        $this->resetUserRoleModelCreationFormObj($user_creation_form_obj, $user_form);
        $this->resetPrevRoleForm($user_form->inputs['prev_role'], $employee_form, $customer_form, $admin_form);
    }

    public function render()
    {   
        $this->updateData();
        return view('livewire.admin.user.create');
    }

    private function resetUserRoleModelCreationFormObj(&$user_creation_form_obj, $user_form)
    {
        unset($user_creation_form_obj[$user_form->inputs['prev_role']]);

        $user_creation_form_obj[$user_form->inputs['role']] = $this->getModelCreationFormObj($user_form->inputs['role']);
    }

    private function resetPrevRoleForm($prev_role, $employee_form, $customer_form, $admin_form)
    {
        ${$this->getFormPropertyName($prev_role)}->resetInputs();
    }

    private function getModelCreationFormObj($model_name)
    {
        return $this->getModelImportPath($model_name)::getModelCreationFormObj();
    }

    private function getModelImportPath($model_name)
    {
        return 'App\Models\\' . $model_name;
    }

    private function getFormPropertyName($model_name)
    {
        return strtolower($model_name) . '_form';
    }
}
