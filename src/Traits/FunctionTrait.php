<?php

namespace  Sashagm\Social\Traits;


use Exception;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait FunctionTrait
{


    private function feedback($method)
    {
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
            $class = $item['class'];
            $method = $item['method'];
            $params = $item['params'];
            call_user_func_array([$class, $method], $params);
        }
    }




}