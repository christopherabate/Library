<?php
namespace DemoBundle;
use Library\Crud\Model;

/**
 * Classe de gestion des projets du bundle.
 *
 * @package Edel
 * @subpackage Uranus
 */
class Type extends Model
{
    /**
     * Tableau d’initialisation contienant le descriptif complet des colonnes et des types de valeurs attendues.
     *
     * @var array
     */
    public static $attributes = array(
        'id' => array ( // nom de la colonne dans la BDD
            'private' => true, // n'apparait pas dans les formulaires
            'type' => 'unique', // voir les types traités par l'objet et les formulaires
            'required' => true, // obligatoires pour le formulaire
        ),
        'nom' => array (
            'type' => 'text',
            'display' => 'type', // nom d'affichage du champ
            'help' => 'Projet interne, web, autre ?', // aide à la saisie
            'required' => true, // obligatoires pour le formulaire
        ),
    );
}
