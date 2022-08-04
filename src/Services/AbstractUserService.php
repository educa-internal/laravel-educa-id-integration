<?php

namespace Tutor\Id\Services;

use App\Exceptions\CustomException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Boolean;
use Tutor\Id\Exceptions\TutorIdException;
use Tutor\Id\Services\Contracts\UserServiceInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Tutor\Id\Services\Socialite\TutorUser;

abstract class AbstractUserService implements UserServiceInterface
{
    public function getOrModifySystemUserFromTutorUser(TutorUser $tutorUser): ?Model
    {
        $validateTutorUser = $this->validateTutorUser($tutorUser);
        if (!$validateTutorUser) {
            throw new TutorIdException(get_tutor_id_message('invalid_account'));
        }

        $systemUser = $this->getSystemUserFromTutorUser($tutorUser);

        if (!$systemUser) {
            $systemUser = $this->createSystemUserFromTutorUser($tutorUser);
        } else {
            $systemUser = $this->compareAndModifyDeviationsUser($systemUser, $tutorUser);
        }

        return $systemUser;
    }

    public function validateTutorUser(TutorUser &$tutorUser)
    {
        $tutorUserOriginal = $tutorUser->user;
        $tutorRoles = $tutorUserOriginal['resource_access'][config('services.tutor-id.client_id')]['roles'] ?? [];

        if (empty($tutorRoles)) {
            Log::warning(__CLASS__ . '-' . __FUNCTION__ . ' Tutor user dont have tutor role , tutor user = ' . json_encode($tutorUser->user));
            return false;
        }

        $systemRoles = $this->validateTutorRoleAndGetSystemRole($tutorRoles);
        if (!$systemRoles) {
            Log::warning(__CLASS__ . '-' . __FUNCTION__ . ' Tutor role invalid , tutor user = ' . json_encode($tutorUser->user));
            return false;
        }

        $tutorUser->setSystemRoles($systemRoles);

        return true;
    }

    abstract protected function validateTutorRoleAndGetSystemRole(array $tutorRoles): ?array;

    abstract protected function getSystemUserFromTutorUser(TutorUser $tutorUser): ?Model;

    abstract protected function createSystemUserFromTutorUser(TutorUser $tutorUser): ?Model;

    private function compareAndModifyDeviationsUser(Model $systemUser, TutorUser $tutorUser): ?Model
    {
        $compare = $this->compareSystemUserWithTutorUser($systemUser, $tutorUser);
        if (!$compare) {
            $update = $this->updateSystemUserFromTutorUser($systemUser, $tutorUser);
            if (!$update) {
                Log::warning(__CLASS__ . '-' . __FUNCTION__ . ' Update systerm user from tutor user fail , system user = ' . json_encode($systemUser) . ' , tutor user = ' . json_encode($tutorUser));
                throw new TutorIdException(get_tutor_id_message('internal_error'));
            }
            $systemUser->refresh();
        }
        return $systemUser;
    }

    abstract protected function compareSystemUserWithTutorUser(Model $systemUser, $tutorUser): ?bool;

    abstract protected function updateSystemUserFromTutorUser(Model $systemUser, $tutorUser): ?Model;

    public function redirectWhenLoginSuccess()
    {
        return redirect('/');
    }

    public function redirectWhenLoginFail($message)
    {
        return redirect('/');
    }

    public function logout()
    {
        $urlLogout = sprintf(config('services.tutor-id.url_logout') . '?redirect_uri=%s', config('services.tutor-id.redirect'));
        return redirect($urlLogout);
    }

    public function redirectWhenLogout()
    {
        return redirect('/');
    }

}
