<?php
// Déclaration des routes
$app->route('', function() { echo Library\Core\I18n::__('welcome'); });
$app->route('error', function() { echo Library\Core\I18n::__('internal_error'); });

$app->route('login', function() { Library\Auth\UserController::login(null, 'recover', 'crud'); });
$app->route('logout', function() { Library\Auth\UserController::logout('login'); });
$app->route('recover', function() { Library\Auth\UserController::recover(null, 'login'); });

$app->route('users', function() { (new Library\Crud\Controller('Library\Auth\User'))->index(); });
$app->route('user/create', function() { (new Library\Crud\Controller('Library\Auth\User', 'users'))->create(); });
$app->route('user/update/(\w+)', function($user) { (new Library\Crud\Controller('Library\Auth\User', 'users'))->update($user); });

$app->route('(\d+)', function($id) { echo $id; }, Library\Core\Session::get('auth'), 'login');

$app->route('modules', function() { DemoBundle\ModuleController::index(); });
$app->route('module/nouveau', function() { DemoBundle\ModuleController::create(); });
$app->route('module/(\d+)', function($id) { DemoBundle\ModuleController::view($id); });
$app->route('module/editer/(\d+)', function($id) { DemoBundle\ModuleController::update($id); });
$app->route('module/supprimer/(\d+)', function($id) { DemoBundle\ModuleController::delete($id); });

$app->route('crud', function() { (new Library\Crud\Controller('DemoBundle\Module'))->index(); });
$app->route('crud/(\d+)', function($id) { (new Library\Crud\Controller('DemoBundle\Module', 'crud'))->view($id); });
$app->route('crud/create', function() { (new Library\Crud\Controller('DemoBundle\Module', 'crud'))->create(); });
$app->route('crud/update/(\d+)', function($id) { (new Library\Crud\Controller('DemoBundle\Module', 'crud'))->update($id); });
$app->route('crud/delete/(\d+)', function($id) { (new Library\Crud\Controller('DemoBundle\Module', 'crud'))->delete($id); });

$app->route('projet', function() { (new Library\Crud\Controller('DemoBundle\Projet'))->index(); });
$app->route('projet/(\d+)', function($id) { (new Library\Crud\Controller('DemoBundle\Projet', 'crud'))->view($id); });
$app->route('projet/create', function() { (new Library\Crud\Controller('DemoBundle\Projet', 'crud'))->create(); });
$app->route('projet/update/(\d+)', function($id) { (new Library\Crud\Controller('DemoBundle\Projet', 'crud'))->update($id); });
$app->route('projet/delete/(\d+)', function($id) { (new Library\Crud\Controller('DemoBundle\Projet', 'crud'))->delete($id); });

// Définition de la route vers les erreurs
$app->error_route = 'error';
