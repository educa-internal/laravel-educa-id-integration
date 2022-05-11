<?php

namespace Tutor\Id\Services\Contracts;

use Illuminate\Database\Eloquent\Model;
use Tutor\Id\Services\Socialite\TutorUser;

interface UserServiceInterface
{
    public function getOrModifySystemUserFromTutorUser(TutorUser $tutorUser): ?Model;

    public function redirectWhenLoginSuccess();

    public function redirectWhenLoginFail($message);

    public function logout();

    public function redirectWhenLogout();
}
