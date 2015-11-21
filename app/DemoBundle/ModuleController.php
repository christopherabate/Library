<?php

/**
 *
 */

namespace DemoBundle;
use Library\Core\App;
use Library\Crud\Controller;

class ModuleController extends Controller
{
    public static $model = 'DemoBundle\Module';
    public static $index_route = 'modules';
}