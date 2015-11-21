<?php

/**
 *
 */

namespace Library\Form;
use Library\Core\Template;
use Library\Core\Session;
use Library\Core\Helper;
use Library\Core\I18n;

class Form
{
    const ERROR_MANDATORY_FIELD = 'Le champ est obligatoire.';
    const ERROR_TECHNICAL_ISSUE = 'Une erreur technique est survenue.';
    const ERROR_SPAM = 'Une erreur technique est survenue. (Spotted by IP)';
    const ERROR_CAPTCHA = 'Une erreur technique est survenue. (Spotted by captcha)';
    const ERROR_HONEYPOT = 'Une erreur technique est survenue (Spotted by honeypot).';
    const ERROR_STRLEN = 'Taille de texte incorrecte.';
    const ERROR_PASSWORD_MATCH = 'Saisir le même mot de passe.';
    const ERROR_VALID_EMAIL = 'Merci de saisir une adresse e-mail valide.';
    const ERROR_VALID_TELEPHONE = 'Merci de saisir un numéro de téléphone valide.';
    const ERROR_VALID_URL = 'Merci de saisir une URL valide.';
    const ERROR_VALID_POSTCODE = 'Merci de saisir un code postal valide.';
    const ERROR_RANGE = 'valeur incorrecte.';

    public $name;
    public $action;
    public $method;
    public $enctype;
    public $view_path;
    public $attributes = array();
    public $errors = array();
    private $antispam = false;
    private $IP_blacklist = array();
    private $captcha = null;
    private $honeypot = null;
    private $buttons = array();
    private $fields = array();

    /**
     *
     */
    public function __construct($name, $attributes, $action = null, $method = 'post', $enctype = 'multipart/form-data') {
        if ($name && !empty($attributes)) {
            $this->name = $name;
            $this->attributes = $attributes;
            $this->action = ($action) ? htmlentities($action) : '#'.$this->name;
            $this->method = $method;
            $this->enctype = $enctype;
        }

        if (defined('FORM_TEMPLATE_PATH')) $this->view_path = FORM_TEMPLATE_PATH;
    }

    /**
     *
     */
    public function validate($user_input)
    {
        foreach ($this->attributes as $field => $attribute) {
            if (isset($attribute['required']) && empty($user_input[$field])) {
                $this->attributes[$field]['error'] = self::ERROR_MANDATORY_FIELD;
            } elseif (!empty($user_input[$field])) {
                $this->attributes[$field]['value'] = Helper::clean($user_input[$field]);

                if ($attribute['type'] === 'text') {
                    $minimum = (!empty($attribute['minimum'])) ? $attribute['minimum'] : 0;
                    $maximum = (!empty($attribute['maximum'])) ? $attribute['maximum'] : 255;
                    if (!Helper::checkStrlen($user_input[$field])) $this->attributes[$field]['error'] = self::ERROR_STRLEN;
                } elseif ($attribute['type'] === 'password') {
                    //if ($user_input[$field] !== $user_input[$field]['confirm']) $this->attributes[$field]['error'] = self::ERROR_PASSWORD_MATCH;
                } elseif ($attribute['type'] === 'textarea') {
                } elseif ($attribute['type'] === 'email') {
                    if (!Helper::checkEmail($user_input[$field])) $this->attributes[$field]['error'] = self::ERROR_VALID_EMAIL;
                } elseif ($attribute['type'] === 'telephone') {
                    if (!Helper::checkTelephone($user_input[$field])) $this->attributes[$field]['error'] = self::ERROR_VALID_TELEPHONE;
                } elseif ($attribute['type'] === 'url') {
                    if (!Helper::checkUrl($user_input[$field])) $this->attributes[$field]['error'] = self::ERROR_VALID_URL;
                } elseif ($attribute['type'] === 'postcode') {
                    if (!Helper::checkPostcode($user_input[$field])) $this->attributes[$field]['error'] = self::ERROR_VALID_URL;
                } elseif ($attribute['type'] === 'rte') {
                } elseif ($attribute['type'] === 'range') {
                    $minimum = (!empty($attribute['minimum'])) ? $attribute['minimum'] : 0;
                    $maximum = (!empty($attribute['maximum'])) ? $attribute['maximum'] : 100;
                    $step = (!empty($attribute['step'])) ? $attribute['step'] : 1;
                    if (!Helper::checkStrlen($user_input[$field]) && ($user_input[$field] % $step) == 0) $this->attributes[$field]['error'] = self::ERROR_RANGE;
                } elseif ($attribute['type'] === 'choice') {
                } elseif ($attribute['type'] === 'model') {
                }
            }
        }

        if ($this->antispam) {
            if (!Helper::checkIP($this->IP_blacklist) || !Helper::checkSpam(15)) $this->errors['spam'] = self::ERROR_TECHNICAL_ISSUE;
        }

        if ($this->captcha) {
            if ($user_input['captcha'] != $this->captcha) $this->errors['captcha'] = self::ERROR_TECHNICAL_ISSUE;
        }

        if ($this->honeypot) {
            if (!empty($user_input[$this->honeypot])) $this->errors['honeypot'] = array('name' => $this->honeypot, 'message' => self::ERROR_HONEYPOT);
        }

        foreach ($this->attributes as $field => $attribute) {
            if (isset($attribute['error'])) {
                $name = (isset($attribute['display'])) ? $attribute['display'] : $field;
                $this->errors[$field] = array('name' => $name, 'message' => $attribute['error']);
            }
        }

        return (empty($this->errors)) ? true : false;
    }

    /**
     *
     */
    public function addAntispam($IP_blacklist)
    {
        $this->antispam = true;
        if (!empty($IP_blacklist)) $this->IP_blacklist = $IP_blacklist;
    }

    /**
     *
     */
    public function addCaptcha($name)
    {
        $this->captcha = $name;
    }

    /**
     *
     */
    public function addHoneypot($name)
    {
        $this->honeypot = $name;
    }

    /**
     *
     */
    public function addButton($name, $type)
    {
        $button = ($this->view_path) ? new Template($this->view_path.'/button.php') : new Template(rtrim(dirname(__FILE__), '\/'.'/').'/Views/button.php');
        $button->set('name', $name);
        $button->set('type', $type);

        $button->prepare();

        array_push($this->buttons, $button->view);
    }

    /**
     *
     */
    public function addField($name, $attribute)
    {
        $field = ($this->view_path) ? new Template($this->view_path.'/'.$attribute['type'].'.php') : new Template(rtrim(dirname(__FILE__), '\/'.'/').'/Views/'.$attribute['type'].'.php');

        $field->set('form', $this->name);
        $field->set('name', $name);

        (isset($attribute['display'])) ? $field->set('label', $attribute['display']) : $field->set('label', $name);
        (isset($attribute['required'])) ? $field->set('required', 'required') : $field->set('required', '');
        (isset($attribute['placeholder'])) ? $field->set('placeholder', $attribute['placeholder']) : $field->set('placeholder', '');
        (isset($attribute['value'])) ? $field->set('value', $attribute['value']) : ((isset($attribute['default'])) ? $field->set('value', $attribute['default']) : $field->set('value', ''));
        (isset($attribute['error'])) ? $field->set('error', $attribute['error']) : $field->set('error', '');
        (isset($attribute['help'])) ? $field->set('help', $attribute['help']) : $field->set('help', '');

        if ($attribute['type'] === 'range') {
            (isset($attribute['minimum'])) ? $field->set('minimum', $attribute['minimum']) : $field->set('minimum', 0);
            (isset($attribute['maximum'])) ? $field->set('maximum', $attribute['maximum']) : $field->set('maximum', 100);
            (isset($attribute['step'])) ? $field->set('step', $attribute['step']) : $field->set('step', 1);
        } elseif ($attribute['type'] === 'choice') {
            $field->set('list', $attribute['list']);
        } elseif ($attribute['type'] === 'model') {
            (isset($attribute['unique_column'])) ? $field->set('unique_column', $attribute['unique_column']) : $field->set('unique_column', 'id');
            (isset($attribute['value_column'])) ? $field->set('value_column', $attribute['value_column']) : $field->set('value_column', 'id');

            $field->set('models', $attribute['model']::collect());
        }

        $field->prepare();

        array_push($this->fields, $field->view);
    }

    /**
     *
     */
    public function build()
    {
        $form = ($this->view_path) ? new Template($this->view_path.'/form.php') : new Template(rtrim(dirname(__FILE__), '\/'.'/').'/Views/form.php');

        foreach ($this->attributes as $name => $attribute) {
            $this->addField($name, $attribute);
        }

        if ($this->captcha) {
            $this->addField('captcha', $attribute);
        }

        if ($this->honeypot) {
            $this->addField($this->honeypot, array('type' => 'honeypot'));
        }

        $form->set('name', $this->name);
        $form->set('action', $this->action);
        $form->set('method', $this->method);
        $form->set('enctype', $this->enctype);
        $form->set('fields', $this->fields);
        $form->set('buttons', $this->buttons);

        $form->prepare();

        return $form->view;
    }
}
