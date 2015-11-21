<?php

/**
 *
 */

namespace Library\Crud;
use Library\Core\App;
use Library\Core\Template;
use Library\Core\Session;
use Library\Core\Helper;
use Library\Core\I18n;
use Library\Form\Form;
use Library\Crud\Model;

class Controller
{
    public static $model;
    public static $index_route;

    /**
     *
     */
    public function __construct($model = null, $index_route = '')
    {
        if ($model) self::$model = $model;
        self::$index_route = $index_route;
    }

    /**
     *
     */
    public static function index($template = null)
    {
        $model = static::$model;

        $view = ($template) ? new Template($template) : new Template(rtrim(dirname(__FILE__), '\/'.'/').'/Views/index.php');
        $view->set('flash', Session::flash());
        $view->set('index', $model::collect());
        $view->render();
    }

    /**
     *
     */
    public static function view($id, $template = null)
    {
        $model = static::$model;

        if ($get_model = $model::get($id)) {
            $view = ($template) ? new Template($template) : new Template(rtrim(dirname(__FILE__), '\/'.'/').'/Views/view.php');
            $view->set('model', $get_model);
            $view->render();
        } else {
            Session::flash('error', I18n::__('item_view_error', array('item' => (new \ReflectionClass($model))->getShortName())));
            App::redirect(static::$index_route);
        }
    }

    /**
     *
     */
    public static function create($template = null)
    {
        self::update(null, $template);
    }

    /**
     *
     */
    public static function update($id = null, $template = null)
    {
        $model = static::$model;

        if (!$id || $model::get($id)) {
            $form = new Form((new \ReflectionClass($model))->getShortName(), $model::getFormAttributes());
            $form->addAntispam(array(1, 2, 3));
            $form->addHoneypot('test');
            $form->addButton('Update', 'submit');

            $view = ($template) ? new Template($template) : new Template(rtrim(dirname(__FILE__), '\/'.'/').'/Views/edit.php');

            if (!empty($_POST[(new \ReflectionClass($model))->getShortName()])) {
                if ($form->validate($_POST[(new \ReflectionClass($model))->getShortName()])) {
                    $model::save($form->attributes, $id);
                    Session::flash('success', I18n::__('item_update_success', array('item' => (new \ReflectionClass($model))->getShortName())));
                    if (Helper::isAjax()) {
                        header("HTTP/1.0 200 Ok");
                        die(static::$index_route);
                    } else {
                        App::redirect(static::$index_route, '200 Ok');
                    }
                } else {
                    if (Helper::isAjax()) {
                        header("HTTP/1.0 400 Bad Request");
                        die($form->build());
                    } else {
                        $view->set('errors', $form->errors);
                    }
                }
            }

            $view->set('id', $id);

            $view->set('form', $form->build());
            $view->render();
        } else {
            Session::flash('error', I18n::__('item_update_error', array('item' => (new \ReflectionClass($model))->getShortName())));
            App::redirect(static::$index_route);
        }
    }

    /**
     *
     */
    public static function delete($id)
    {
        $model = static::$model;

        if ($model::remove($id)) {
            Session::flash('success', I18n::__('item_delete_success', array('item' => (new \ReflectionClass($model))->getShortName())));
            App::redirect(static::$index_route, '200 Ok');
        }
        else {
            Session::flash('error', I18n::__('item_delete_error', array('item' => (new \ReflectionClass($model))->getShortName())));
            App::redirect(static::$index_route);
        }
    }
}
