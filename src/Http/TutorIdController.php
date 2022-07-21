<?php

namespace Tutor\Id\Http;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Tutor\Id\Services\Contracts\UserServiceInterface;
use Tutor\Id\Exceptions\TutorIdException;

class TutorIdController extends Controllers
{
    private $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function redirectToTutorId()
    {
        return Socialite::driver(config('services.tutor-id.diver_name'))
            ->scopes(['profile'])
            ->redirect();
    }

    public function handleTutorIdCallback(Request $request)
    {
        if ($this->isRequestLogout($request)) {
            return $this->logout();
        }

        try {
            $tutorUser = Socialite::driver(config('services.tutor-id.diver_name'))->user();

            $user = $this->userService->getOrModifySystemUserFromTutorUser($tutorUser);

            if ($user) {
                Auth::login($user);
                $redirectAfterLoginSuccess = $this->userService->redirectWhenLoginSuccess();
                return $redirectAfterLoginSuccess;
            }
        } catch (Exception $e) {
            Log::error($e);
        }

        $redirectAfterLoginFail = $this->userService->redirectWhenLoginFail(trans('tutor_id::tutor_id.fail_login'));
        return $redirectAfterLoginFail;
    }

    private function isRequestLogout(Request $request)
    {
        $input = $request->all();
        return empty($input);
    }

    private function logout()
    {
        return $this->userService->redirectWhenLogout();
    }
}
