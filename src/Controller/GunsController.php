<?php

namespace Controller;

use Form\GunsAddForm;
use Repository\DictionaryRepository;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Service\FileUploader;
use Repository\GunsRepository;

class GunsController implements ControllerProviderInterface
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
            ->bind('guns');
        $controller->match('list', [$this, 'listAction'])
            ->bind('guns_list');
        $controller->match('delete/{id}', [$this, 'deleteAction'])
            ->bind('guns_delete');
        $controller->match('show/{id}', [$this, 'showAction'])
            ->bind('guns_show');

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
            $fileUploader = new FileUploader($app['config.photos_directory']);
            $fileName = $fileUploader->upload($data['image_name']);
            $data['image_name'] = $fileName;
            $conn->insert('guns', $data);
            echo 'Dodano broń';
        }

        $this->view['form'] = $form->createView();

        return $app['twig']->render('guns/add.html.twig', array('form' => $form->createView()));
    }

    public function listAction(Application $app, Request $request)
    {
        $gunsRepository = new GunsRepository($app['db']);
        $guns = $gunsRepository->findAllGuns();

        return $app['twig']->render('guns/list.html.twig', array('guns' => $guns));
    }

    public function deleteAction(Application $app, Request $request)
    {
        $gunsRepository = new GunsRepository($app['db']);
        $gunsRepository->deleteGunById($request->get('id'));

        $app['session']->getFlashBag()->add(
            'messages',
            [
                'type'    => 'success',
                'message' => 'Broń została usunięta',
            ]
        );

        return $app->redirect(
            $app['url_generator']->generate('guns_list'),
            301
        );
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

        return $app['twig']->render('guns/show.html.twig', array(
            'gun' => $gun,
            'dictionary' => $dictionaryList
        ));
    }

}