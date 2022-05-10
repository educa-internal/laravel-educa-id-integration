<?php

function get_tutor_id_route_redirect()
{
    return route('auth.tutor_id.redirect');
}

function get_tutor_id_title_login()
{
    return trans('tutor_id::tutor_id.title_login');
}

function get_tutor_id_message_login_fail()
{
    return trans('tutor_id::tutor_id.fail_login');
}
