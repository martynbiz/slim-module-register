<?php
namespace MartynBiz\Slim\Module\Register;

use Slim\App;
use Slim\Container;
use MartynBiz\Slim\Module\ModuleInterface;

use MartynBiz\Slim\Module\Auth;
use MartynBiz\Slim\Module\Core;

class Module implements ModuleInterface
{
    /**
     * Get config array for this module
     * @return array
     */
    public function initDependencies(Container $container)
    {

    }

    /**
     * Initiate app middleware (route middleware should go in initRoutes)
     * @param App $app
     * @return void
     */
    public function initMiddleware(App $app)
    {

    }

    /**
     * Load is run last, when config, dependencies, etc have been initiated
     * Routes ought to go here
     * @param App $app
     * @return void
     */
    public function initRoutes(App $app)
    {
        $container = $app->getContainer();
        $settings = $container->get('settings')['martynbiz-register'];

        $app->group($settings['base_path'], function () use ($app, $container) {

            $app->group('/users', function () use ($app) {
                $app->get('/register',
                    '\MartynBiz\Slim\Module\Register\Controller\RegisterController:register')->setName('register_register');
                $app->post('/register',
                    '\MartynBiz\Slim\Module\Register\Controller\RegisterController:post')->setName('register_post');
            });
        })
        ->add($container->get('csrf'));
    }

    /**
     * Copies files from vendor dir to project tree
     * @param string $dest The root of the project
     * @return void
     */
    public function copyFiles($dirs)
    {
        // copy module settings and template
        $src = __DIR__ . '/../files/modules/*';
        shell_exec("cp -rn $src {$dirs['modules']}");
    }

    /**
     * Removes files from the project tree
     * @param string $dest The root of the project
     * @return void
     */
    public function removeFiles($dirs)
    {
        // remove module settings and template
        if ($path = realpath("{$dirs['modules']}/martynbiz-auth")) {
            shell_exec("rm -rf $path");
        }

        // TODO inform to manually remove db migrations coz they'll fuck up rollback
    }
}
