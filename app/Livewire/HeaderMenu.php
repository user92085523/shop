<?php

namespace App\Livewire;

use App\Enums\Models\User\Role;
use Auth;
use Livewire\Component;

class HeaderMenu extends Component
{
    public $header_menu = [];
    public $category_num = '0';
    public $subcategory_num = '0';
    public $full_path = null;

    public function mount()
    {
        $user_role = Auth::getUser()->role->value;

        if (in_array($user_role, array_map(fn($role) => $role->value, Role::cases()))) {
            $this->header_menu = $this->getHeaderMenu($user_role);
        }
    }
    
    public function render()
    {
        $this->updateFullPath();
        return view('livewire.header-menu');
    }
    
    public function jump()
    {
        return redirect($this->full_path);
    }

    private function updateFullPath()
    {
        $this->full_path = $this->header_menu[$this->category_num]['next'][$this->subcategory_num]['full_path'];
    }

    private function getHeaderMenu($user_role)
    {
        $model = "App\Models\\" . $user_role;

        return $model::getHeaderMenuObj();
    }
}
