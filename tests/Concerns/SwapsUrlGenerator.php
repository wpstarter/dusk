<?php

namespace Laravel\Dusk\Tests\Concerns;

use WpStarter\Container\Container;
use WpStarter\Http\Request;
use WpStarter\Routing\RouteCollection;
use WpStarter\Routing\UrlGenerator;

trait SwapsUrlGenerator
{
    protected function swapUrlGenerator()
    {
        Container::getInstance()->bind('url', function () {
            return new class(new RouteCollection(), new Request()) extends UrlGenerator
            {
                public function route($name, $parameters = [], $absolute = true)
                {
                    $route = '/'.$name.'/'.implode('/', $parameters);

                    if ($absolute) {
                        $route = 'http://www.google.com'.$route;
                    }

                    return $route;
                }
            };
        });
    }
}
