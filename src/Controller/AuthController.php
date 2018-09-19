<?php
/**
 * Auth controller.
 * Kontroler rejestracji, logowania i wylogowywania
 */
namespace Controller;

use Form\LoginType;
use Form\RegisterForm;
use Form\ChangePasswordForm;
use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Repository\UserRepository;

/**
 * Class AuthController.
 */
class AuthController implements ControllerProviderInterface
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
        $controller->match('login', [$this, 'loginAction'])
            ->method('GET|POST')
            ->bind('auth_login');
        $controller->match('logout', [$this, 'logoutAction'])
            ->bind('auth_logout');
        $controller->get('register', [$this, 'registerAction'])
            ->method('GET|POST')
            ->bind('auth_register');
        $controller->get('change_password', [$this, 'changePasswordAction'])
            ->method('GET|POST')
            ->bind('auth_change_password');

        return $controller;
    }

    /**
     * Login action.
     * Funkcja logowania
     * @param \Silex\Application                        $app     Silex application
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP Request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     */
    public function loginAction(Application $app, Request $request)
    {
        $user = ['login' => $app['session']->get('_security.last_username')];
        $form = $app['form.factory']->createBuilder(LoginType::class, $user)->getForm();

        return $app['twig']->render(
            'auth/login.html.twig',
            [
                'form' => $form->createView(),
                'error' => $app['security.last_error']($request),
            ]
        );
    }

    /**
     * Logout action.
     * Funkcja wylogowywania
     * @param \Silex\Application $app Silex application
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP Response
     */
    public function logoutAction(Application $app)
    {
        $app['session']->clear();

        return $app['twig']->render('auth/logout.html.twig', []);
    }

    /**
     * Funkcja rejestracji
     * @param Application $app
     * @param Request     $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function registerAction(Application $app, Request $request)
    {
        $form = $app['form.factory']->createBuilder(
            RegisterForm::class,
            null,
            ['user_repository' => new UserRepository($app['db'])]
        )->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $form->getData();
            $data['password'] = $app['security.encoder.bcrypt']->encodePassword($data['password'], '');
            $data['role_id'] = 2;
            $conn = $app['db'];
            $conn->insert('users', $data);

            $app['session']->getFlashBag()->add(
                'messages',
                [
                    'type'    => 'success',
                    'message' => 'message.registration_success',
                ]
            );

            return $app->redirect(
                $app['url_generator']->generate('auth_login'),
                301
            );
        }

        $this->view['form'] = $form->createView();

        return $app['twig']->render('auth/register.html.twig', array('form' => $form->createView()));
    }

    /**
     * Funkcja zmiany hasła
     * @param Application $app
     * @param Request     $request
     *
     * @return mixed
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function changePasswordAction(Application $app, Request $request)
    {
        $form = $app['form.factory']->createBuilder(ChangePasswordForm::class)->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $userToken = $app['security.token_storage']->getToken();
            $userRep = new UserRepository($app['db']);
            $user = $userRep->getUserByLogin($userToken->getUser()->getUserName());
            $data = $form->getData();
            $pass = $app['security.encoder.bcrypt']->encodePassword($data['password'], '');
            $conn = $app['db'];
            $conn->executeUpdate('UPDATE users SET password = ? WHERE id = ?', array($pass, $user['id']));
            $app['session']->getFlashBag()->add(
                'messages',
                [
                    'type'    => 'success',
                    'message' => 'message.change_password_success',
                ]
            );
        }

        return $app['twig']->render(
            'auth/changePassword.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
