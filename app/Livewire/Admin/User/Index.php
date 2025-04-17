<?php

namespace App\Livewire\Admin\User;

use App\MyHelper\Trait\CustomSearchForm;
use Livewire\Component;

class Index extends Component
{
    use CustomSearchForm;

    public function mount()
    {
        $this->csf_Init(
            ['User', 'Employee'],
        );
    }

    public function render()
    {
        $this->csf_Update();
        return view('livewire.admin.user.index');
    }
}