<?php

namespace Controller;

use Form\GunsAddForm;
use Repository\DictionaryRepository;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class GunsController implements ControllerProviderInterface
{

    const TYPE_FIRST = 1;
    const TYPE_SECOND = 2;

    public static $TYPES = [

    ];


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
            ->bind('guns');

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
        $conn = $app['db'];
        $dictionary = new DictionaryRepository($app['db']);

        $form = $app['form.factory']->createBuilder(GunsAddForm::class, [],[
            'dictionary' => [
                'lockTypes' =>$dictionary->getLockTypes(),
                'ammunitionTypes' => $dictionary->getAmmuntionTypes(),
                'caliberTypes' => $dictionary->getCaliberTypes(),
                'gunTypes' => $dictionary->getGunTypes(),
                'reloadTypes' => $dictionary->getReloadTypes(),
            ]
        ])->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $conn->insert('guns', $data);
            echo 'Dodano broń';
        }

        $this->view['form'] = $form->createView();

        return $app['twig']->render('guns/add.html.twig', array('form' => $form->createView()));
    }

}