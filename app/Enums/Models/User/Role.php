<?php

namespace App\Enums\Models\User;

enum Role: string
{
    case Admin = "Admin";
    case Employee = "Employee";
    case Customer = "Customer";

    public function ja_JP()
    {
        return match ($this) {
            Role::Admin => '管理者',
            Role::Employee => '従業員',
            Role::Customer => '顧客',
        };
    }

    public function loginPagePath()
    {
        return match ($this) {
            Role::Admin => '/admin/login',
            Role::Employee => '/employee/login',
            Role::Customer => '/customer/login',
        };
    }

    public function logoutPath()
    {
        return match ($this) {
            Role::Admin => '/admin/logout',
            Role::Employee => '/employee/logout',
            Role::Customer => '/customer/logout',
        }; 
    }

    public function homePath()
    {
        return match ($this) {
            Role::Admin => '/admin/home',
            Role::Employee => '/employee/home',
            Role::Customer => '/customer/home',
        };
    }

    public static function formOrder()
    {
        return [
            Role::Employee,
            Role::Customer,
            Role::Admin,
        ];
    }
}
