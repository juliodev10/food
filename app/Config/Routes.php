<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Rotas Admin - Usuários
$routes->get('admin/usuarios', 'Admin\Usuarios::index');
$routes->get('admin/usuarios/procurar', 'Admin\Usuarios::procurar');
