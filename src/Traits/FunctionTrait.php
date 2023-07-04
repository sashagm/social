<?php

namespace  Sashagm\Social\Traits;

use Exception;
use Carbon\Carbon;

trait FunctionTrait
{


    private function feedback($method)
    {

        if (!$method) {
            throw new \InvalidArgumentException('Feedback method not specified!');
        }

        switch ($method) {
            case 'before':
                $feedback = config('socials.feedback_before');
                break;

            case 'after':
                $feedback = config('socials.feedback_after');
                break;

            case 'register':
                $feedback = config('socials.feedback_register');
                break;

            default:
                throw new Exception('Invalid feedback method.');
        }

        foreach ($feedback as $item) {
            if (!isset($item['class']) || !isset($item['method'])) {
                throw new Exception("Social auth configuration error: class or method not set!");
            }

            $class = $item['class'];
            $method = $item['method'];
            $params = $item['params'];

            if (!class_exists($class)) {
                throw new Exception("Class {$class} not found.");
            }

            if (!method_exists($class, $method)) {
                throw new Exception("Method {$method} not found in class {$class}.");
            }

            call_user_func_array([$class, $method], $params);
        }
    }


    private function cast_fields($socialUser, $provider)
    {

        if (!$socialUser) {
            throw new \InvalidArgumentException('Social user not specified!');
        }

        if (!$provider) {
            throw new \InvalidArgumentException('Provider not specified!');
        }

        $userData = [
            config('socials.user.name_colum') => $socialUser->getName() ?? $socialUser->getNickname(),
            config('socials.user.email_colum') => $socialUser->getEmail(),
            config('socials.user.pass_colum') => $this->generatePass(),
            config('socials.user.avatar') => $this->getAvatar($socialUser),
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
        ];

        $customFields = config('socials.custom_fields');

        foreach ($customFields as $field => $value) {
            $userData[$field] = $value;
        }

        return $userData;
    }

    private function updateUser($user, $socialUser)
    {

        if (!config('socials.user.auto_update')) {
            throw new Exception("Social auth configuration error: auto_update not set!");
        }

        if (!$user) {
            throw new \InvalidArgumentException('User not specified!');
        }

        if (!$socialUser) {
            throw new \InvalidArgumentException('Social user not specified!');
        }

        if (config('socials.user.auto_update')) {

            $update = config('socials.user.update_colum');
            $name = config('socials.user.name_colum');
            $img = config('socials.user.avatar');

            if ($user->$update == 1) {
                $user->$name = $socialUser->getName() ?? $socialUser->getNickname();
                $user->$img  = $socialUser->getAvatar();
                $user->updated_at = Carbon::now();
                $user->save();
            } else {
                $user->updated_at = Carbon::now();
                $user->save();
            }
        } else {
            $user->updated_at = Carbon::now();
            $user->save();
        }
    }

    private function getAvatar($socialUser)
    {
        if (config('socials.user.getAvatar')) {
            return $socialUser->getAvatar();
        } else {
            return config('socials.user.defaultAvatar');
        }
    }
}
