<?php

/**
 *
 */

namespace Library\Auth;
use Library\Core\App;
use Library\Core\Template;
use Library\Core\Session;
use Library\Core\I18n;
use Library\Form\Form;

class UserController extends \Library\Crud\Controller
{
    public static $model = 'Library\Auth\User';

    /**
     *
     */
    public static function login($template = null)
    {
        $user = static::$model;

        $form = new Form((new \ReflectionClass($user))->getShortName(), $user::getFormAttributes());
        $form->addHoneypot('trap');
        $form->addButton('Login', 'submit');

        $view = ($template) ? new Template($template) : new Template(rtrim(dirname(__FILE__), '\/'.'/').'/Views/login.php');

        if (!empty($_POST[(new \ReflectionClass($user))->getShortName()])) {
            if ($form->validate($_POST[(new \ReflectionClass($user))->getShortName()])) {
                if ($get_user = $user::get($_POST[(new \ReflectionClass($user))->getShortName()][$user::getUniqueColumn()])) {
                    if ($_POST[(new \ReflectionClass($user))->getShortName()][$user::getPasswordColumn()] === $get_user[$user::getPasswordColumn()]['value']) {
                        $view->set('user', $get_user);
                        Session::set('auth', true);
                        Session::flash('success', I18n::__('auth_login_success'));
                        unset($_POST[(new \ReflectionClass($user))->getShortName()]);
                    } else {
                        Session::flash('error', I18n::__('auth_password_error'));
                    }
                } else {
                    Session::flash('error', I18n::__('auth_username_error', array('username' => $form->attributes[$user::getUniqueColumn()]['value'])));
                }
            } else {
                $view->set('errors', $form->errors);
            }
        }

        $view->set('flash', Session::flash());
        $view->set('form', $form->build());
        $view->render();
    }

    /**
     *
     */
    public static function logout()
    {
        Session::set('auth', false);
    }
}
