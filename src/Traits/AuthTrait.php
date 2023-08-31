<?php

namespace  Sashagm\Social\Traits;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


trait AuthTrait
{

    use BuildsLoggers;

    public function authenticateUser($user, $password)
    {

        $pass = config('socials.user.pass_colum');
        
        $em = config('socials.user.email_colum');

        $name =  config('socials.user.name_colum');

        $storedPassword = $user->$pass;

        $hashedPassword = $this->encryptPassword($password);

        if ($storedPassword === $hashedPassword) {

            $this->checkSocialsIsActive($user);

            $this->isAccess($user->$em);

            Auth::login($user);

            if (config('socials.logger.log_login')) { 

                $this->logger('info', "The user: {$user->$name} has successfully logged in: {$_SERVER['REMOTE_ADDR']}"); 
            }

            return true;
            

        } else {

            return false;
        }
    }



    public function encryptPassword($password)
    {
        $method = config('socials.genPass.method'); // default method

        $secret = config('socials.genPass.secret');

        switch ($method) {

            case "bcrypt":
                return password_hash($password, PASSWORD_BCRYPT);
                break;

            case "md2":
                return hash("md2", $password);
                break;

            case "md4":
                return hash("md4", $password);
                break;

            case "md5":
                if (config('socials.genPass.viewReg')) {
                    return strtoupper(md5($password . $secret));
                } else {
                    return md5($password . $secret);
                }
                break;

            case "password_hash":
                return password_hash($password, PASSWORD_DEFAULT);
                break;

            case "sha1":
                return hash("sha1", $password);
                break;

            case "sha224":
                return hash("sha224", $password);
                break;

            case "sha256":
                return hash("sha256", $password);
                break;

            case "sha384":
                return hash("sha384", $password);
                break;

            case "sha512":
                return hash("sha512", $password);
                break;

            case "sha512/224":
                return hash("sha512/224", $password);
                break;

            case "sha512/256":
                return hash("sha512/256", $password);
                break;

            case "sha3-224":
                return hash("sha3-224", $password);
                break;

            case "sha3-256":
                return hash("sha3-256", $password);
                break;

            case "sha3-384":
                return hash("sha3-384", $password);
                break;

            case "sha3-512":
                return hash("sha3-512", $password);
                break;

            case "ripemd128":
                return hash("ripemd128", $password);
                break;

            case "ripemd160":
                return hash("ripemd160", $password);
                break;

            case "ripemd256":
                return hash("ripemd256", $password);
                break;

            case "ripemd320":
                return hash("ripemd320", $password);
                break;

            case "whirlpool":
                return hash("whirlpool", $password);
                break;

            case "tiger128,3":
                return hash("tiger128,3", $password);
                break;

            case "tiger160,3":
                return hash("tiger160,3", $password);
                break;

            case "tiger192,3":
                return hash("tiger192,3", $password);
                break;

            case "tiger128,4":
                return hash("tiger128,4", $password);
                break;

            case "tiger160,4":
                return hash("tiger160,4", $password);
                break;

            case "tiger192,4":
                return hash("tiger192,4", $password);
                break;

            case "snefru":
                return hash("snefru", $password);
                break;

            case "snefru256":
                return hash("snefru256", $password);
                break;

            case "gost":
                return hash("gost", $password);
                break;

            case "gost-crypto":
                return hash("gost-crypto", $password);
                break;

            case "adler32":
                return hash("adler32", $password);
                break;

            case "crc32":
                return hash("crc32", $password);
                break;

            case "crc32b":
                return hash("crc32b", $password);
                break;

            case "crc32c":
                return hash("crc32c", $password);
                break;

            case "fnv132":
                return hash("fnv132", $password);
                break;

            case "fnv1a32":
                return hash("fnv1a32", $password);
                break;

            case "fnv164":
                return hash("fnv164", $password);
                break;

            case "fnv1a64":
                return hash("fnv1a64", $password);
                break;

            case "joaat":
                return hash("joaat", $password);
                break;

            case "murmur3a":
                return hash("murmur3a", $password);
                break;

            case "murmur3c":
                return hash("murmur3c", $password);
                break;

            case "murmur3f":
                return hash("murmur3f", $password);
                break;

            case "xxh32":
                return hash("xxh32", $password);
                break;

            case "xxh64":
                return hash("xxh64", $password);
                break;

            case "xxh3":
                return hash("xxh3", $password);
                break;

            case "xxh128":
                return hash("xxh128", $password);
                break;

            case "pbkdf2":
                return hash_pbkdf2("sha256", $password, "salt", 1000, 32);
                break;

            case "base64":
                return base64_encode($password);
                break;

            default:
                return $password;
                break;
        }
    }
}
