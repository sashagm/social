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
                if (config('socials.genPass.viewReg')) {
                    $pass = strtoupper(md5($this->generateString($filter) . $secret));
                } else {
                    $pass = md5($this->generateString($filter) . $secret);
                }
                break;

            case 'md2':
                $pass = hash("md2", $this->generateString($filter));
                break;

            case 'md4':
                $pass = hash("md4", $this->generateString($filter));
                break;

            case 'sha224':
                $pass = hash("sha224", $this->generateString($filter));
                break;

            case 'sha384':
                $pass = hash("sha384", $this->generateString($filter));
                break;

            case 'sha512/224':
                $pass = hash("sha512/224", $this->generateString($filter));
                break;

            case 'sha512/256':
                $pass = hash("sha512/256", $this->generateString($filter));
                break;

            case 'sha3-224':
                $pass = hash("sha3-224", $this->generateString($filter));
                break;

            case 'sha3-256':
                $pass = hash("sha3-256", $this->generateString($filter));
                break;

            case 'sha3-384':
                $pass = hash("sha3-384", $this->generateString($filter));
                break;

            case 'sha3-512':
                $pass = hash("sha3-512", $this->generateString($filter));
                break;

            case 'ripemd128':
                $pass = hash("ripemd128", $this->generateString($filter));
                break;

            case 'ripemd160':
                $pass = hash("ripemd160", $this->generateString($filter));
                break;

            case 'ripemd256':
                $pass = hash("ripemd256", $this->generateString($filter));
                break;

            case 'ripemd320':
                $pass = hash("ripemd320", $this->generateString($filter));
                break;

            case 'snefru':
                $pass = hash("snefru", $this->generateString($filter));
                break;

            case 'snefru256':
                $pass = hash("snefru256", $this->generateString($filter));
                break;

            case 'tiger128,3':
                $pass = hash("tiger128,3", $this->generateString($filter));
                break;

            case 'tiger160,3':
                $pass = hash("tiger160,3", $this->generateString($filter));
                break;

            case 'tiger192,3':
                $pass = hash("tiger192,3", $this->generateString($filter));
                break;

            case 'tiger128,4':
                $pass = hash("tiger128,4", $this->generateString($filter));
                break;

            case 'tiger160,4':
                $pass = hash("tiger160,4", $this->generateString($filter));
                break;

            case 'tiger192,4':
                $pass = hash("tiger192,4", $this->generateString($filter));
                break;

            case 'gost':
                $pass = hash("gost", $this->generateString($filter));
                break;

            case 'gost-crypto':
                $pass = hash("gost-crypto", $this->generateString($filter));
                break;


            case 'adler32':
                $pass = hash("adler32", $this->generateString($filter));
                break;

            case 'crc32b':
                $pass = hash("crc32b", $this->generateString($filter));
                break;

            case 'crc32c':
                $pass = hash("crc32c", $this->generateString($filter));
                break;

            case 'fnv132':
                $pass = hash("fnv132", $this->generateString($filter));
                break;

            case 'fnv1a32':
                $pass = hash("fnv1a32", $this->generateString($filter));
                break;

            case 'fnv164':
                $pass = hash("fnv164", $this->generateString($filter));
                break;

            case 'fnv1a64':
                $pass = hash("fnv1a64", $this->generateString($filter));
                break;

            case 'joaat':
                $pass = hash("joaat", $this->generateString($filter));
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

                case 'custom-string':
                    $characters = config('socials.genPass.custom_string');
                    break;

                case 'custom-hard':
                    $characters = config('socials.genPass.custom_hard');
                    break;

                case 'custom-unique':
                    $characters = config('socials.genPass.custom_unique');
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
    
            $strings = [];
            for ($i = 0; $i < config('socials.genPass.generation_stages'); $i++) {
                $string = '';
                for ($j = 0; $j < $length; $j++) {
                    $string .= $characters[rand(0, strlen($characters) - 1)];
                }
                $strings[] = $string;
            }

    
            return $strings[array_rand($strings)];
        }
    }




}
