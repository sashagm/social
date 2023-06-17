<?php

namespace  Sashagm\Social\Traits;


use Illuminate\Database\Eloquent\Relations\MorphToMany;
use App\Models\User;

trait GenPassTrait
{



    private function generatePass()
    {
        $method = config('socials.genPass.method');
        $filter = config('socials.genPass.filter');
        $secret = config('socials.genPass.secret');

        switch ($method) {

            case 'bcrypt':
                $pass = bcrypt($this->generateString($filter));
                break;

            case 'md5':
                if (config('socials.genPass.viewReg')){
                    $pass = strtoupper(md5($this->generateString($filter) . $secret));
                } else {
                    $pass = md5($this->generateString($filter) . $secret);
                }
                break;

            case 'password_hash':
                $pass = password_hash($this->generateString($filter), PASSWORD_DEFAULT);
                break;

            case 'sha1':
                $pass = sha1($this->generateString($filter));
                break;

            case 'sha256':
                $pass = hash('sha256', $this->generateString($filter));
                break;

            case 'sha512':
                $pass = hash('sha512', $this->generateString($filter));
                break;

            case 'pbkdf2':
                $password = $this->generateString($filter);
                $iterations = 1000;
                $salt = random_bytes(16);
                $pass = hash_pbkdf2("sha256", $password, $salt, $iterations, 20);
                break;

            case 'base64':
                $pass = base64_encode($this->generateString($filter));
                break;

            case 'crc32':
                $pass = hash("crc32", $this->generateString($filter));
                break;

            case 'whirlpool':
                $pass = hash("whirlpool", $this->generateString($filter));
                break;                


            default:
                $pass = bcrypt($this->generateString($filter));
                break;
        }

        return $pass;
    }

    private function generateString($filter)
    {

        if (config('socials.genPass.default_gen')) {

            return config('socials.genPass.default_pass');
        } else {

            switch ($filter) {

                case 'string':
                    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    break;

                case 'number':
                    $characters = '0123456789';
                    break;

                case 'hard':
                    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                    break;

                case 'hard-unique':
                    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-+=[]{}|:;<>,.?/~';
                    break;

                case 'rus-string':
                    $characters = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ';
                    break;

                case 'rus-hard':
                    $characters = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ0123456789';
                    break;

                case 'rus-unique':
                    $characters = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ0123456789!@#$%^&*()_-+=[]{}|:;<>,.?/~';
                    break;

                default:
                    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                    break;
            }

            $minLength = config('socials.genPass.min');
            $maxLength = config('socials.genPass.max');
            $stableLength = config('socials.genPass.stable_length');


            if ($stableLength) {
                $length = config('socials.genPass.length');
            } else {
                $length = rand($minLength, $maxLength);
            }


            $string = '';
            for ($i = 0; $i < $length; $i++) {
                $string .= $characters[rand(0, strlen($characters) - 1)];
            }
            return $string;
        }
    }



}