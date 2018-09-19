<?php

namespace Controller;

use Form\GunsAddForm;
use Form\GunsEditForm;
use Repository\DictionaryRepository;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Service\FileUploader;
use Repository\GunsRepository;

/**
 * Class GunsController
 */
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
        $controller->match('list/{page}', [$this, 'listAction'])
            ->value('page', 1)
            ->bind('guns_list');
        $controller->match('delete/{id}', [$this, 'deleteAction'])
            ->bind('guns_delete');
        $controller->match('edit/{id}', [$this, 'editAction'])
            ->bind('guns_edit');
        $controller->match('show/{id}', [$this, 'showAction'])
            ->bind('guns_show');

        return $controller;
    }

    /**
     * Index action.
     * Funkcja dodawania nowej broni
     * @param \Silex\Application                        $app     Silex application
     * @param \Symfony\Component\HttpFoundation\Request $request Request object
     *
     * @return string Response
     */
    public function indexAction(Application $app, Request $request)
    {
        $conn = $app['db'];
        $dictionary = new DictionaryRepository($app['db']);

        $form = $app['form.factory']->createBuilder(GunsAddForm::class, [], [
            'dictionary' => $dictionary->getAllTypesForAddForm(),
        ])->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $fileUploader = new FileUploader($app['config.photos_directory']);
            $fileName = $fileUploader->upload($data['image_name']);
            $data['image_name'] = $fileName;
            $conn->insert('guns', $data);
            $app['session']->getFlashBag()->add(
                'messages',
                [
                    'type'    => 'success',
                    'message' => 'message.gun_add_success',
                ]
            );

            return $app->redirect(
                $app['url_generator']->generate('guns_list'),
                301
            );
        }

        $this->view['form'] = $form->createView();

        return $app['twig']->render('guns/add.html.twig', array('form' => $form->createView()));
    }

    /**
     * Edit action.
     * Funkcja edytowania broni
     * @param Application $app
     * @param Request     $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function editAction(Application $app, Request $request)
    {
        $conn = $app['db'];
        $dictionary = new DictionaryRepository($app['db']);
        $gunRepository = new GunsRepository($app['db']);
        $gun = $gunRepository->findGunById($request->get('id'));

        $form = $app['form.factory']->createBuilder(GunsEditForm::class, $gun, [
            'dictionary' => $dictionary->getAllTypesForAddForm(),
        ])->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $conn->update('guns', $data, $gun);
            $app['session']->getFlashBag()->add(
                'messages',
                [
                    'type'    => 'success',
                    'message' => 'message.gun_edit_success',
                ]
            );

            return $app->redirect(
                $app['url_generator']->generate('guns_list'),
                301
            );
        }

        $this->view['form'] = $form->createView();

        return $app['twig']->render('guns/edit.html.twig', array('form' => $form->createView(), 'gun' => $gun));
    }

    /**
     * Funkcja wyświetlannia listy dostępnych w rejestrze broni
     * @param Application $app
     * @param int         $page
     *
     * @return mixed
     */
    public function listAction(Application $app, $page = 1)
    {
        $gunsRepository = new GunsRepository($app['db']);
        $guns = $gunsRepository->findAllGuns($page);
        $pages = $gunsRepository->countAllPages();

        if ($page > $pages) {
            return $app->redirect(
                $app['url_generator']->generate('guns_list'),
                301
            );
        }

        return $app['twig']->render('guns/list.html.twig', array('paginator' => $guns));
    }

    /**
     * Funkcja usuwania broni z rejestru
     * @param Application $app
     * @param Request     $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Application $app, Request $request)
    {
        $gunsRepository = new GunsRepository($app['db']);
        $gunsRepository->deleteGunById($request->get('id'));

        $app['session']->getFlashBag()->add(
            'messages',
            [
                'type'    => 'success',
                'message' => 'message.gun_delete_success',
            ]
        );

        return $app->redirect(
            $app['url_generator']->generate('guns_list'),
            301
        );
    }

    /**
     * Funkcja podglądu broni
     * @param Application $app
     * @param Request     $request
     *
     * @return mixed
     */
    public function showAction(Application $app, Request $request)
    {
        $gunsRepository = new GunsRepository($app['db']);
        $gun = $gunsRepository->findGunById($request->get('id'));
        $dictionary = new DictionaryRepository($app['db']);
        $dictionaryList =  $dictionary->getAllTypes();

        return $app['twig']->render('guns/show.html.twig', array(
            'gun' => $gun,
            'dictionary' => $dictionaryList,
        ));
    }
}
