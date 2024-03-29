<?php

namespace App\Validators;

use GuzzleHttp\Client;

class ReCaptcha
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        $client = new Client;
        $response = $client->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'form_params' =>
                [
                    'secret' => env('CONF_reCaptchaSecret'),
                    'response' => $value,
                    'remoteip' => request()->getClientIp()
                ]
            ]
        );
        $body = json_decode((string) $response->getBody(), true);

        if (isset($body['success']) && $body['success'] === true) {
            return true;
        } else {
            return false;
        }
    }
}
