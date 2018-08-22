<?php
/**
 * Hello controller.
 *
 * @copyright (c) 2016 Tomasz Chojna
 * @link http://epi.chojna.info.pl
 */
namespace Controller;

use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Repository\TagsRepository;

/**
 * Class HelloController.
 */
class HelloController implements ControllerProviderInterface
{
    /**
     * Routing settings.
     *
     * @param \Silex\Application $app Silex application
     *
     * @return \Silex\ControllerCollection Result
     */
    public function connect(Application $app)
    {
        $controller = $app['controllers_factory'];
        $controller->match('/', array($this, 'indexAction'))
            ->bind('hello');

        return $controller;
    }

    /**
     * Index action.
     *
     * @param \Silex\Application                        $app     Silex application
     * @param \Symfony\Component\HttpFoundation\Request $request Request object
     *
     * @return string Response
     */
    public function indexAction(Application $app, Request $request)
    {
           $sql = "SELECT * FROM users";
            $post = $app['db']->fetchAssoc($sql);
            var_dump($post);exit;
        var_dump(10);exit;
        $name = $request->get('name', '');
        $guns = [
            1 => [
                'name' => 'Glock',
            ],
            2 => [
                'name' => 'M4A1',
            ],
            3 => [
                'name' => 'AK47'
            ]
        ];
        return $app['twig']->render('hello/index.html.twig', [
            'name' => $name,
            'guns' => $guns
        ]);
    }



}