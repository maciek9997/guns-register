<?php
/**
 * Hello controller.
 *
 * @copyright (c) 2016 Tomasz Chojna
 * @link http://epi.chojna.info.pl
 */
namespace Controller;

use Repository\CollectionRepository;
use Repository\UserRepository;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Repository\GunsRepository;
use Repository\DictionaryRepository;

/**
 * Class HomeController
 */
class GunsUserController implements ControllerProviderInterface
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
        $controller->match('list', [$this, 'listAction'])
            ->bind('user_guns_list');
        $controller->match('guns_show/{id}', [$this, 'showAction'])
            ->bind('user_guns_show');
        $controller->match('collection', [$this, 'collectionAction'])
            ->bind('user_guns_collection');
        $controller->match('add/{id}', [$this, 'addAction'])
            ->bind('user_guns_add');

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
    public function listAction(Application $app, Request $request)
    {
        $gunsRepository = new GunsRepository($app['db']);
        $guns = $gunsRepository->findAllGuns();

        return $app['twig']->render('user/guns/list.html.twig', array('guns' => $guns));
    }

    public function showAction(Application $app, Request $request)
    {
        $gunsRepository = new GunsRepository($app['db']);
        $gun = $gunsRepository->findGunById($request->get('id'));
        $dictionary = new DictionaryRepository($app['db']);
        $dictionaryList =  [
            'lockTypes' => array_flip($dictionary->getLockTypes()),
            'ammunitionTypes' => array_flip($dictionary->getAmmuntionTypes()),
            'caliberTypes' => array_flip($dictionary->getCaliberTypes()),
            'gunTypes' => array_flip($dictionary->getGunTypes()),
            'reloadTypes' => array_flip($dictionary->getReloadTypes()),
        ];

        return $app['twig']->render('user/guns/show.html.twig', array(
            'gun' => $gun,
            'dictionary' => $dictionaryList
        ));
    }

    public function addAction(Application $app, Request $request)
    {
        $userToken = $app['security.token_storage']->getToken();
        $userRep = new UserRepository($app['db']);
        $user = $userRep->getUserByLogin($userToken->getUser()->getUserName());
        $collectionRep = new CollectionRepository($app['db']);
        $exist = $collectionRep->findisExist($user['id'], $request->get('id'));

        if (!$exist) {
            $collectionRep->addGun($user['id'], $request->get('id'));
            $app['session']->getFlashBag()->add(
                'messages',
                [
                    'type' => 'success',
                    'message' => 'Dodano broń do kolekcji',
                ]
            );
        } else {
            $app['session']->getFlashBag()->add(
                'messages',
                [
                    'type' => 'info',
                    'message' => 'Ta broń jest już w twojej kolekcji',
                ]
            );
        }

        return $app->redirect(
            $app['url_generator']->generate('user_guns_collection'),
            301
        );
    }

    public function collectionAction(Application $app, Request $request)
    {
        $userToken = $app['security.token_storage']->getToken();
        $userRep = new UserRepository($app['db']);
        $user = $userRep->getUserByLogin($userToken->getUser()->getUserName());
        $gunsRepository = new CollectionRepository($app['db']);
        $guns = $gunsRepository->findMyGuns($user['id']);

        return $app['twig']->render('user/guns/collection.html.twig', array('guns' => $guns));
    }

}