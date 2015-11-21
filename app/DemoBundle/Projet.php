<?php
namespace DemoBundle;
use Library\Crud\Model;

/**
 * Classe de gestion des projets du bundle.
 *
 * @package Edel
 * @subpackage Uranus
 */
class Projet extends Model
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
            'display' => 'projet', // nom d'affichage du champ
            'required' => true, // obligatoires pour le formulaire
        ),
        'description' => array (
            'type' => 'rte',
        ),
        'type' => array (
            'type' => 'model',
            'display' => 'type de projet', // nom d'affichage du champ
            'model' => 'DemoBundle\Type',
            'unique_column' => 'id',
            'value_column' => 'nom',
            'required' => true, // obligatoires pour le formulaire
        ),
    );
}
