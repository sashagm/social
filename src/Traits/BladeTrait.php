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

            if (!config('socials.isActive')) {
                throw new \Exception(trans('social-auth::socials.offline'));
            }

            $providers = config('socials.providers');

            if (!$providers) {
                throw new \InvalidArgumentException('Social auth configuration error: providers not set!');
            }

            $html = '';

            foreach ($providers as $provider) {
                if (!in_array($provider, $routes['auth_login'])) {
                    throw new \InvalidArgumentException('Invalid social provider.');
                }

                $html .= '<a href="' . route($routes['auth_login'][$provider]) . '" class=' . $class . ' style=' . $style . '>' . trans('social-auth::socials.link_auth') . ucfirst($provider) . '</a>&nbsp;';
            }

            return $html;
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

            if (!config('socials.isActive')) {
                throw new \Exception(trans('social-auth::socials.offline'));
            }

            $providers = config('socials.providers');

            if (!$providers) {
                throw new \InvalidArgumentException('Social auth configuration error: providers not set!');
            }

            $html = '';

            foreach ($providers as $provider => $icon) {
                if (!in_array($provider, $routes['auth_login'])) {
                    throw new \InvalidArgumentException('Invalid social provider.');
                }

                $html .= '<a href="' . route($routes['auth_login'][1], $provider) . '" class=' . $class . ' style=' . $style . '>' . $icon . '</a>&nbsp;';
            }

            return $html;
        });
    }
}
