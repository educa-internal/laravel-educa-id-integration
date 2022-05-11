<?php

function get_tutor_id_route_redirect_login()
{
    return route('auth.tutor_id.redirect');
}

function get_tutor_id_title_login()
{
    return trans('tutor_id::tutor_id.title_login');
}

function get_tutor_id_message($message)
{
    return trans('tutor_id::tutor_id.' . $message);
}

function tutor_id_logout(){
    $userService = app(\Tutor\Id\Services\Contracts\UserServiceInterface::class);
    return $userService->logout();
}

