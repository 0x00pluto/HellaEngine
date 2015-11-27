<?php
/**
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 15/11/17
 * Time: 下午8:18
 */

namespace hellaEngine\Foundation\Bootstraps;


use hellaEngine\Application\Application;
use hellaEngine\Interfaces\Foundation\Bootstraps\Bootstrap;

class LoadServiceRoute implements Bootstrap
{

    public function bootstrap(Application $app)
    {
        $routeFileName = $app->appPath() . DIRECTORY_SEPARATOR . 'routes.php';
        if (file_exists($routeFileName)) {
            require $routeFileName;
        }
    }
}