<?php

namespace  Sashagm\Social\Traits;


use Exception;

use Illuminate\Support\Facades\Blade;


trait BladeTrait
{



    private function blade()
    {
        Blade::directive('socials', function ($expression) {
            $class = '';
            $style = '';
            $params = explode(',', $expression);
            $routes = config('socials.routes');
        
            if (count($params) > 0) {
                foreach ($params as $param) {
                    if (stristr($param, 'class')) {
                        $class = str_replace('class=', '', $param);
                    }
        
                    if (stristr($param, 'style')) {
                        $style = str_replace('style=', '', $param);
                    }
                }
            }
        
            if (config('socials.isActive')) {
                $providers = config('socials.providers');

                if (!config('socials.providers')) {
                    throw new Exception("Social auth configuration error: providers not set!");
                }
        
                $html = '';
        
                foreach ($providers as $provider) {
                    $html .= '<a href="' . route($routes['auth_login'][1], $provider) . '" class=' . $class . ' style=' . $style . '>' . trans('social-auth::socials.link_auth') .  ucfirst($provider) . '</a>&nbsp;';
                }
        
                return $html;
            } else {
                return '<h3>' . trans('social-auth::socials.offline') . '</h3>';
            }
        });


    }
 
    private function blade_btn()
    {
        Blade::directive('socialsBtn', function ($expression) {
            $class = '';
            $style = '';
            $params = explode(',', $expression);
            $routes = config('socials.routes');
        
            if (count($params) > 0) {
                foreach ($params as $param) {
                    if (stristr($param, 'class')) {
                        $class = str_replace('class=', '', $param);
                    }
        
                    if (stristr($param, 'style')) {
                        $style = str_replace('style=', '', $param);
                    }
                }
            }
        
            if (config('socials.isActive')) {
                $providers = config('socials.providers');

                if (!config('socials.providers')) {
                    throw new Exception("Social auth configuration error: providers not set!");
                }
        
                $html = '';
        
                foreach ($providers as $provider => $icon) {
                    $html .= '<a href="' . route($routes['auth_login'][1], $provider) . '" class=' . $class . ' style=' . $style . '>' . $icon . '</a>&nbsp;';
                }
        
                return $html;
            } else {
                return '<h3>' . trans('social-auth::socials.offline') . '</h3>';
            }
        });
    }






}