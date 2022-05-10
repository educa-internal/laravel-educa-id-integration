<?php

namespace Tutor\Id\Services\Socialite;

class User extends \Laravel\Socialite\Two\User
{
    public $systemRoles;

    public function setSystemRoles($systemRoles)
    {
        $this->systemRoles = $systemRoles;
    }

    public function getSystemRoles()
    {
        return $this->systemRoles;
    }
}