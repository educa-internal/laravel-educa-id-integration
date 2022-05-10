<?php

namespace Tutor\Id\Services\Socialite;

use Illuminate\Support\Arr;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Tutor\Id\Services\Socialite\User;

class SocialiteTutorIdProvider extends AbstractProvider implements ProviderInterface
{
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(config('services.tutor-id.url_authorize'), $state);
    }

    protected function getTokenUrl()
    {
        return config('services.tutor-id.url_get_token');
    }

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get(config('services.tutor-id.url_get_user_by_token'), [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }


    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id' => Arr::get($user, 'sub'),
            'name' => Arr::get($user, 'name'),
            'nickname' => Arr::get($user, 'name'),
            'email' => Arr::get($user, 'email'),
        ]);
    }

    protected function getTokenFields($code)
    {
        return Arr::add(
            parent::getTokenFields($code), 'grant_type', 'authorization_code'
        );
    }
}
