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
    public static function login($template = null, $recover_route = null, $redirect_route = null)
    {
        Session::delete('auth');
        
        $user = static::$model;

        $form = new Form((new \ReflectionClass($user))->getShortName(), $user::getLoginAttributes(), '', 'post', 'multipart/form-data', rtrim(dirname(__FILE__), '\/'.'/').'/Views/form');
        $form->addAntispam(1);
        $form->addHoneypot(time());
        $form->addButton(I18n::__('auth_login_button'), 'submit');

        if ($user::getEmailColumn()) {
            $form->addLink($recover_route);
        }

        $view = ($template) ? new Template($template) : new Template(rtrim(dirname(__FILE__), '\/'.'/').'/Views/login.php');

        if (!empty($_POST[(new \ReflectionClass($user))->getShortName()])) {
            $form->clean($_POST[(new \ReflectionClass($user))->getShortName()]);

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
        }

        $view->set('flash', Session::flash());
        $view->set('form', $form->build());

        if (Session::get('auth') && Session::get('redirect_referer')) App::redirect(Session::get('redirect_referer'), 200);
        elseif (Session::get('auth') && $redirect_route) App::redirect($redirect_route, 200);
        elseif (Session::get('auth')) App::redirect(static::$index_route, 200);
        else $view->render();
    }

    /**
     *
     */
    public static function logout($redirect_route = null)
    {
        Session::delete('auth');

        if ($redirect_route) App::redirect($redirect_route, 200);
        else App::redirect(static::$index_route, 200);
    }

    /**
     *
     */
    public static function recover($template = null, $redirect_route = null)
    {
        $user = static::$model;

        $form = new Form((new \ReflectionClass($user))->getShortName(), $user::getRecoverAttributes(), '', 'post', 'multipart/form-data', rtrim(dirname(__FILE__), '\/'.'/').'/Views/form');
        $form->addAntispam(1);
        $form->addHoneypot(time());
        $form->addCaptcha('captcha');
        $form->addButton(I18n::__('auth_recover_button'), 'submit');

        $view = ($template) ? new Template($template) : new Template(rtrim(dirname(__FILE__), '\/'.'/').'/Views/recover.php');

        if (!empty($_POST[(new \ReflectionClass($user))->getShortName()])) {
            $form->clean($_POST[(new \ReflectionClass($user))->getShortName()]);

            if ($get_user = $user::get($_POST[(new \ReflectionClass($user))->getShortName()][$user::getUniqueColumn()])) {
                if ($_POST[(new \ReflectionClass($user))->getShortName()][$user::getEmailColumn()] === $get_user[$user::getEmailColumn()]['value']) {
                    $view->set('user', $get_user);
                    Session::flash('success', I18n::__('auth_recover_success'));
                    unset($_POST[(new \ReflectionClass($user))->getShortName()]);
                    if ($redirect_route) App::redirect($redirect_route, 200);
                } else {
                    Session::flash('error', I18n::__('auth_email_error'));
                }
            } else {
                Session::flash('error', I18n::__('auth_username_error', array('username' => $form->attributes[$user::getUniqueColumn()]['value'])));
            }
        }
        
        $view->set('flash', Session::flash());
        $view->set('form', $form->build());

        $view->render();
    }
}
