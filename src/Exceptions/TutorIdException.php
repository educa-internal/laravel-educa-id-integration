<?php

namespace Tutor\Id\Exceptions;

use Exception;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use Tutor\Id\Services\Contracts\UserServiceInterface;

class TutorIdException extends Exception
{
    public function render($request)
    {
        $message = $this->getMessage();
        /** @var UserServiceInterface $userService */
        $userService = app(UserServiceInterface::class);
        return $userService->redirectWhenLoginFail($message);
    }
}
