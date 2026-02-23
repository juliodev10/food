<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setAutoRoute(false);

$routes->get('/', 'Home::index');

$routes->group('', ['filter' => 'visitante'], static function ($routes) {
    $routes->get('login', 'Login::novo');
    $routes->post('login/criar', 'Login::criar');
    $routes->get('password/esqueci', 'Password::esqueci');
    $routes->post('password/processaesqueci', 'Password::processaEsqueci');
    $routes->get('password/reset/(:alphanum)', 'Password::reset/$1');
    $routes->post('password/processareset/(:alphanum)', 'Password::processaReset/$1');
});

$routes->group('', ['filter' => 'login'], static function ($routes) {
    $routes->get('login/logout', 'Login::logout');
    $routes->get('login/mostraMensagemLogout', 'Login::mostraMensagemLogout');
});

$routes->group('admin', ['filter' => 'login'], static function ($routes) {
    $routes->group('', ['filter' => 'admin'], static function ($routes) {
        $routes->get('home', 'Admin\\Home::index');

        $routes->get('categorias', 'Admin\\Categorias::index');
        $routes->get('categorias/procurar', 'Admin\\Categorias::procurar');
        $routes->get('categorias/criar', 'Admin\\Categorias::criar');
        $routes->post('categorias/cadastrar', 'Admin\\Categorias::cadastrar');
        $routes->get('categorias/show/(:num)', 'Admin\\Categorias::show/$1');
        $routes->get('categorias/editar/(:num)', 'Admin\\Categorias::editar/$1');
        $routes->post('categorias/atualizar/(:num)', 'Admin\\Categorias::atualizar/$1');
        $routes->match(['get', 'post'], 'categorias/excluir/(:num)', 'Admin\\Categorias::excluir/$1');
        $routes->get('categorias/desfazerExclusao/(:num)', 'Admin\\Categorias::desfazerExclusao/$1');

        $routes->get('extras', 'Admin\\Extras::index');
        $routes->get('extras/procurar', 'Admin\\Extras::procurar');
        $routes->get('extras/criar', 'Admin\\Extras::criar');
        $routes->post('extras/cadastrar', 'Admin\\Extras::cadastrar');
        $routes->get('extras/show/(:num)', 'Admin\\Extras::show/$1');
        $routes->get('extras/editar/(:num)', 'Admin\\Extras::editar/$1');
        $routes->post('extras/atualizar/(:num)', 'Admin\\Extras::atualizar/$1');
        $routes->match(['get', 'post'], 'extras/excluir/(:num)', 'Admin\\Extras::excluir/$1');
        $routes->get('extras/desfazerExclusao/(:num)', 'Admin\\Extras::desfazerExclusao/$1');

        $routes->get('medidas', 'Admin\\Medidas::index');
        $routes->get('medidas/procurar', 'Admin\\Medidas::procurar');
        $routes->get('medidas/criar', 'Admin\\Medidas::criar');
        $routes->post('medidas/cadastrar', 'Admin\\Medidas::cadastrar');
        $routes->get('medidas/show/(:num)', 'Admin\\Medidas::show/$1');
        $routes->get('medidas/editar/(:num)', 'Admin\\Medidas::editar/$1');
        $routes->post('medidas/atualizar/(:num)', 'Admin\\Medidas::atualizar/$1');
        $routes->match(['get', 'post'], 'medidas/excluir/(:num)', 'Admin\\Medidas::excluir/$1');
        $routes->get('medidas/desfazerExclusao/(:num)', 'Admin\\Medidas::desfazerExclusao/$1');

        $routes->get('produtos', 'Admin\\Produtos::index');
        $routes->get('produtos/procurar', 'Admin\\Produtos::procurar');
        $routes->get('produtos/criar', 'Admin\\Produtos::criar');
        $routes->post('produtos/cadastrar', 'Admin\\Produtos::cadastrar');
        $routes->get('produtos/show/(:num)', 'Admin\\Produtos::show/$1');
        $routes->get('produtos/editar/(:num)', 'Admin\\Produtos::editar/$1');
        $routes->post('produtos/atualizar/(:num)', 'Admin\\Produtos::atualizar/$1');
        $routes->match(['get', 'post'], 'produtos/excluir/(:num)', 'Admin\\Produtos::excluir/$1');
        $routes->get('produtos/desfazerExclusao/(:num)', 'Admin\\Produtos::desfazerExclusao/$1');
        $routes->get('produtos/editarimagem/(:num)', 'Admin\\Produtos::editarImagem/$1');
        $routes->post('produtos/upload/(:num)', 'Admin\\Produtos::upload/$1');
        $routes->get('produtos/imagem/(:any)', 'Admin\\Produtos::imagem/$1');
        $routes->get('produtos/extras/(:num)', 'Admin\\Produtos::extras/$1');
        $routes->post('produtos/cadastrarextras/(:num)', 'Admin\\Produtos::cadastrarExtras/$1');
        $routes->post('produtos/excluirextra/(:num)/(:num)', 'Admin\\Produtos::excluirExtra/$1/$2');

        $routes->get('usuarios', 'Admin\\Usuarios::index');
        $routes->get('usuarios/procurar', 'Admin\\Usuarios::procurar');
        $routes->get('usuarios/criar', 'Admin\\Usuarios::criar');
        $routes->post('usuarios/cadastrar', 'Admin\\Usuarios::cadastrar');
        $routes->get('usuarios/show/(:num)', 'Admin\\Usuarios::show/$1');
        $routes->get('usuarios/editar/(:num)', 'Admin\\Usuarios::editar/$1');
        $routes->post('usuarios/atualizar/(:num)', 'Admin\\Usuarios::atualizar/$1');
        $routes->match(['get', 'post'], 'usuarios/excluir/(:num)', 'Admin\\Usuarios::excluir/$1');
        $routes->get('usuarios/desfazerExclusao/(:num)', 'Admin\\Usuarios::desfazerExclusao/$1');
    });
});