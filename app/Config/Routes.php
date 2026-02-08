<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Rotas Admin - Usuários
$routes->get('admin/usuarios', 'Admin\Usuarios::index');
$routes->get('admin/usuarios/procurar', 'Admin\Usuarios::procurar');
$routes->get('admin/usuarios/editar/(:num)', 'Admin\Usuarios::editar/$1');
$routes->post('admin/usuarios/atualizar/(:num)', 'Admin\Usuarios::atualizar/$1');
$routes->get('admin/usuarios/show/(:num)', 'Admin\Usuarios::show/$1');
