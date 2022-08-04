<?php

namespace Tutor\Id\Services\Socialite;

use Illuminate\Support\Arr;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Tutor\Id\Services\Socialite\TutorUser;
use Illuminate\Support\Facades\Log;

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

    public function getAccessTokenResponse($code)
    {
        $tokenUrl = $this->getTokenUrl();
        $formParams = $this->getTokenFields($code);

        $response = $this->getHttpClient()->post($tokenUrl, [
            'headers' => ['Accept' => 'application/json'],
            'form_params' => $formParams,
            'http_errors' => false
        ]);
        $result = json_decode($response->getBody(), true);

        Log::debug(sprintf(__CLASS__ . '-' . __FUNCTION__ . " Get access token , url = %s , form_params = %s , response = %s , httpcode = %s",
            $tokenUrl,
            json_encode($formParams),
            json_encode($result),
            $response->getStatusCode()
        ));

        return $result;
    }

    protected function getUserByToken($token)
    {
        $urlGetUserByToken = config('services.tutor-id.url_get_user_by_token');

        $response = $this->getHttpClient()->get($urlGetUserByToken, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
            'http_errors' => false
        ]);
        $result = json_decode($response->getBody(), true);

        Log::debug(sprintf(__CLASS__ . '-' . __FUNCTION__ . " Get user by token , url = %s , token = %s , response = %s , httpcode = %s",
            $urlGetUserByToken,
            $token,
            json_encode($result),
            $response->getStatusCode()
        ));

        return $result;
    }


    protected function mapUserToObject(array $user)
    {
        return (new TutorUser)->setRaw($user)->map([
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
