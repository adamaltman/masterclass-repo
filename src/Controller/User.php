<?php

namespace Masterclass\Controller;

use Aura\View\View;
use Masterclass\Model\UserMysqlDataStore as UserModel;
use Masterclass\Request;

class User
{
    protected $userModel;
    protected $request;
    protected $view;

    public function __construct(UserModel $model, Request $request, View $view)
    {
        $this->userModel = $model;
        $this->request = $request;
        $this->view = $view;
    }

    public function create()
    {
        $error = null;

        // Do the create
        if ($this->request->getPostParam('create')) {

            $username = $this->request->getPostParam('username');
            $password = $this->request->getPostParam('password'); $_POST['password'];
            $passwordCheck = $this->request->getPostParam('password_check');
            $email = $this->request->getPostParam('email');

            if (empty($username)
                || empty($email)
                || empty($password)
                || empty($passwordCheck)
            ) {
                $error = 'You did not fill in all required fields.';
            }

            if (is_null($error)) {
                if (!$this->request->validateEmail($email)) {
                    $error = 'Your email address is invalid';
                }
            }

            if (is_null($error)) {
                if ($password != $passwordCheck) {
                    $error = "Your passwords didn't match.";
                }
            }

            if (is_null($error)) {
                if ($this->userModel->userExists($username)) {
                    $error = 'Your chosen username already exists. Please choose another.';
                }
            }

            if (is_null($error)) {
                $this->userModel->createUser($username, $email, $password);
                header("Location: /user/login");
                exit;
            }
        }
        // Show the create form

        $this->view->setLayout('layout');
        $this->view->setView('user-create');
        $this->view->setData(
            [
                'error' => $error,
            ]
        );
        echo $this->view->__invoke();
    }

    public function account()
    {
        $error = null;
        if (!$this->request->getSession()->get('AUTHENTICATED')) {
            header("Location: /");
            exit;
        }

        $username = $_SESSION['username'];
        $password = $this->request->getPostParam('password') ?? null;

        if ($this->request->getPostParam('updatepw') !== null) {
            $passwordCheck = $this->request->getPostParam('password_check');
            if (empty($password)
                || empty($passwordCheck)
                || $password != $passwordCheck
            ) {
                $error = 'The password fields were blank or they did not match. Please try again.';
            } else {
                $this->userModel->updatePassword($username, $password);
                // @TODO: odd, there is no error.
                $error = 'Your password was changed.';
            }
        }

        $userModel = $this->userModel->loadUserByUsername($username);

        $this->view->setLayout('layout');
        $this->view->setView('user-account');
        $this->view->setData(
            [
                'error' => $error,
                'username' => $username,
                'email' => $userModel->getEmail()
            ]
        );
        echo $this->view->__invoke();
    }

    public function login()
    {
        $error = null;
        // Do the login
        if ($this->request->getPostParam('login')) {
            $username = $this->request->getPostParam('user');
            $password = $this->request->getPostParam('pass');

            if ($this->userModel->checkCredentials($username, $password)) {
                $this->request->getSession()->regenerateId();
                $this->request->getSession()->add('username', $username);
                $this->request->getSession()->add('AUTHENTICATED', true);
                header("Location: /");
                exit;
            } else {
                $error = 'Your username/password did not match.';
            }
        }

        $this->view->setLayout('layout');
        $this->view->setView('user-login');
        $this->view->setData(
            [
                'error' => $error,
            ]
        );
        echo $this->view->__invoke();
    }

    public function logout()
    {
        // Log out, redirect
        $this->request->getSession()->destroy();
        header("Location: /");
    }
}
