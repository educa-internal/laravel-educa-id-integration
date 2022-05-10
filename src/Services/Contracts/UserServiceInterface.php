<?php

namespace Tutor\Id\Services\Contracts;

use Illuminate\Database\Eloquent\Model;
use Tutor\Id\Services\Socialite\User;

interface UserServiceInterface
{
    public function getOrModifySystemUserFromTutorUser(User $tutorUser): ?Model;

    public function redirectWhenLoginSuccess();

    public function redirectWhenLoginFail();
}
