<?php
namespace DemoBundle;
use Library\Crud\Model;

/**
 * Classe de gestion des projets du bundle.
 *
 * @package Edel
 * @subpackage Uranus
 */
class Module extends Model
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
            'display' => 'version', // nom d'affichage du champ
            'required' => true, // obligatoires pour le formulaire
        ),
        'description' => array (
            'type' => 'rte',
            'help' => 'Description longue de la version',
        ),
        'avancement' => array (
            'type' => 'range',
            'minimum' => 0,
            'maximum' => 100,
            'step' => 10,
            'default' => 0, // valeur par défaut dans la BDD et les formulaires
            'help' => 'Pourcentage d\'avancement du projet',
        ),
        'projet' => array (
            'type' => 'model',
            'model' => 'DemoBundle\Projet',
            'value_column' => 'nom',
            'required' => true, // obligatoires pour le formulaire
        ),
        'etat' => array (
            'type' => 'choice',
            'display' => 'état', // nom d'affichage du champ
            'list' => array (
                1 => 'Actif',
                2 => 'Livré',
                3 => 'Annulé',
                4 => 'Stand-by',
            ),
            'default' => 1, // valeur par défaut dans la BDD et les formulaires
            'required' => true, // obligatoires pour le formulaire
        ),
    );
}
