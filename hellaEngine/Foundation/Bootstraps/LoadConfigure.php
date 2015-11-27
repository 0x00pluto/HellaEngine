<?php
/**
 * Created by PhpStorm.
 * User: zhipeng
 * Date: 15/10/21
 * Time: 下午2:02
 */

namespace hellaEngine\Foundation\Bootstraps;


use hellaEngine\Application\Application;
use hellaEngine\Interfaces\Foundation\Bootstraps\Bootstrap;
use Illuminate\Config\Repository;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Class LoadConfigure
 * @package hellaEngine\Foundation\Bootstraps
 */
class LoadConfigure implements Bootstrap
{

    /**
     * @param Application $app
     */
    public function bootstrap(Application $app)
    {
        $finder = new Finder();
        $iterator = $finder->files()
            ->name("*.php")
            ->depth(0)
            ->in($app->configPath());

        $config = new Repository();
        $app->instance('config', $config);


        foreach ($iterator as $file) {
            if ($file instanceof SplFileInfo) {
                $realPath = $file->getRealPath();
                $config->set(basename($realPath, ".php"),
                    require $realPath);
            }
        }
    }
}