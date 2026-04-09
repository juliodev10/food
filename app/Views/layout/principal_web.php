<!DOCTYPE html>
<html lang="zxx" dir="ltr">

<!-- BEGIN head -->


<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->

<head>

    <!-- Meta tags -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Food Delivery | <?= $this->renderSection('titulo') ?></title>
    <!-- Stylesheets -->
    <link href="<?php echo site_url('web/'); ?>src/assets/css/bootstrap.min.css" type="text/css" rel="stylesheet"
        media="all" />
    <link href="<?php echo site_url('web/'); ?>src/assets/css/bootstrap-theme.min.css" type="text/css" rel="stylesheet"
        media="all" />
    <link href="<?php echo site_url('web/'); ?>src/assets/css/fonts.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo site_url('web/'); ?>src/assets/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo site_url('web/'); ?>src/assets/css/slick.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo site_url('web/'); ?>src/assets/css/slick-theme.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo site_url('web/'); ?>src/assets/css/aos.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo site_url('web/'); ?>src/assets/css/scrolling-nav.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo site_url('web/'); ?>src/assets/css/bootstrap-datepicker.css" type="text/css"
        rel="stylesheet" />
    <link href="<?php echo site_url('web/'); ?>src/assets/css/bootstrap-datetimepicker.css" type="text/css"
        rel="stylesheet" />
    <link href="<?php echo site_url('web/'); ?>src/assets/css/touch-sideswipe.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo site_url('web/'); ?>src/assets/css/jquery.fancybox.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo site_url('web/'); ?>src/assets/css/main.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo site_url('web/'); ?>src/assets/css/responsive.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="<?php echo site_url('admin/'); ?>css/footer.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180"
        href="<?php echo site_url('web/'); ?>src/assets/img/favicon/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="256x256"
        href="<?php echo site_url('web/'); ?>src/assets/img/favicon/android-chrome-256x256.png">
    <link rel="icon" type="image/png" sizes="192x192"
        href="<?php echo site_url('web/'); ?>src/assets/img/favicon/android-chrome-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32"
        href="<?php echo site_url('web/'); ?>src/assets/img/favicon/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16"
        href="<?php echo site_url('web/'); ?>src/assets/img/favicon/favicon-16x16.png" />
    <link rel="icon" type="image/png" href="<?php echo site_url('web/'); ?>src/assets/img/favicon/favicon.ico" />
    <link rel="manifest" href="<?php echo site_url('web/'); ?>src/assets/img/site.html" />
    <link rel="mask-icon" href="<?php echo site_url('web/'); ?>src/assets/img/favicon/safari-pinned-tab.svg"
        color="#5bbad5" />
    <meta name="msapplication-TileColor" content="#990100" />
    <meta name="theme-color" content="#ffffff" />

    <style>
        body.pagina-detalhes-produto .navigation {
            position: relative;
            top: auto;
            background-color: rgba(153, 1, 0, .92);
        }

        body.pagina-detalhes-produto .navbar-container {
            top: 0;
        }

        body.pagina-detalhes-produto .navbar {
            margin: 0;
        }

        body.pagina-detalhes-produto #header {
            margin-bottom: 1.5rem;
        }

        .navbar-nav>li>a {
            line-height: 30px;
        }

        .navbar-brand.site-brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding-top: 8px;
            padding-bottom: 8px;
        }

        .navbar-brand.site-brand img {
            width: auto;
            height: 52px;
            max-height: 52px;
        }

        .site-brand-text {
            font-family: 'Montserrat-Bold';
            font-size: 24px;
            line-height: 1;
            color: #ffffff;
            letter-spacing: 0.3px;
            white-space: nowrap;
            position: relative;
            top: 0;
        }

        .rmenu_logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .rmenu_logo .site-brand-text {
            top: 0;
        }

        .rmenu_logo img {
            width: auto;
            height: 40px;
            max-height: 40px;
        }

        .mobile-header-actions {
            float: right;
            margin-top: 8px;
            margin-right: -16px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
        }

        .mobile-auth-opposite {
            float: left;
            margin-top: 10px;
            margin-left: 12px;
            display: inline-flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px;
            max-width: calc(100vw - 190px);
        }

        .mobile-header-actions .right_menu_icon {
            position: relative;
            top: 0;
            margin-left: 10px;
        }

        .mobile-header-actions .mobile-cart-link,
        .mobile-header-actions .right_menu_icon {
            display: inline-block;
            color: #fff;
            margin-left: 10px;
            text-decoration: none;
        }

        .mobile-header-actions .mobile-cart-count {
            font-size: 14px;
            margin-left: 4px;
            font-weight: 700;
        }

        .mobile-auth-links {
            display: inline-flex;
            align-items: center;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 6px;
        }

        .mobile-auth-link {
            display: inline-block;
            padding: 4px 10px;
            border: 1px solid rgba(255, 255, 255, 0.45);
            border-radius: 16px;
            color: #fff;
            font-size: 12px;
            line-height: 1.2;
            text-decoration: none;
            white-space: nowrap;
        }

        .mobile-auth-link:hover,
        .mobile-auth-link:focus {
            color: #fff;
            text-decoration: none;
            background: rgba(255, 255, 255, 0.15);
        }

        .mobile-auth-link--danger {
            border-color: rgba(255, 255, 255, 0.7);
        }

        .body-wrapper>.container .alert {
            white-space: normal;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        #footer .footer_border.footer_border-contato {
            min-height: 0;
            margin-bottom: 8px;
        }

        @media (max-width: 420px) {
            .mobile-auth-link {
                font-size: 11px;
                padding: 4px 8px;
            }

            .navbar-brand.site-brand {
                gap: 6px;
            }

            .navbar-brand.site-brand img {
                height: 38px;
                max-height: 38px;
            }

            .site-brand-text {
                font-size: 18px;
            }
        }

        @media (max-width: 767.98px) {
            .body-wrapper>.container {
                padding-left: 12px;
                padding-right: 12px;
            }

            .site-brand-text {
                top: 0;
            }

            .body-wrapper>.container .alert {
                width: 100%;
                max-width: 100%;
                font-size: 14px;
                line-height: 1.35;
                padding-right: 34px;
            }
        }

        @media (min-width: 768px) {
            .mobile-auth-opposite {
                display: none;
            }

            .mobile-header-actions {
                display: none;
            }

            .navbar-brand.site-brand img {
                height: 58px;
                max-height: 58px;
            }
        }
    </style>
    <!-- Essa section redenderizáos estilos de cada view para ester esse layout-->
    <?= $this->renderSection('estilos') ?>

</head>
<!-- END head -->

<!-- BEGIN body -->

<?php
$uri = service('uri');
$isPaginaDetalhesProduto = $uri->getSegment(1) === 'produto' && $uri->getSegment(2) === 'detalhes';
$isPaginaCustomizarProduto = $uri->getSegment(1) === 'produto' && $uri->getSegment(2) === 'customizar';
$isPaginaCheckout = $uri->getSegment(1) === 'checkout';
$isPaginaPrincipal = $uri->getTotalSegments() === 0;
$isPaginaConta = $uri->getSegment(1) === 'conta';
$expedienteHoje = expedienteHoje();
$telefoneContato = '+55 35 9105-2828';
$enderecoContato = 'Tv. Lemos, 86 - Pratápolis';
?>

<body data-spy="scroll" data-target=".navbar" data-offset="50"
    class="<?= $isPaginaDetalhesProduto ? 'pagina-detalhes-produto' : ''; ?>">

    <!-- BEGIN  Loading Section -->
    <div class="loading-overlay">
        <div class="spinner">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <!-- END Loading Section -->

    <!-- BEGIN body wrapper -->
    <div class="body-wrapper">

        <!-- Begin header-->
        <header id="header">

            <!-- BEGIN carousel -->
            <?php if (!$isPaginaDetalhesProduto): ?>
                <div id="main-carousel" class="carousel slide" data-ride="carousel">
                    <div class="container pos_rel" style="min-height: 1vh !important;">

                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                            <li data-target="#main-carousel" data-slide-to="0" class="active"></li>
                            <li data-target="#main-carousel" data-slide-to="1"></li>
                            <li data-target="#main-carousel" data-slide-to="2"></li>
                            <li data-target="#main-carousel" data-slide-to="3"></li>
                            <li data-target="#main-carousel" data-slide-to="4"></li>
                        </ol>

                        <!-- Controls -->
                        <a class="left carousel-control" href="#main-carousel" role="button" data-slide="prev">
                            <i class="fa fa-angle-left" aria-hidden="true"></i>
                        </a>
                        <a class="right carousel-control" href="#main-carousel" role="button" data-slide="next">
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </a>

                        <!-- Wrapper for slides -->
                        <div class="carousel-inner" role="listbox">

                            <!-- Carousel items   -->
                            <div class="item active">
                                <div class="carousel-caption">
                                    <div class="fadeUp item_img">
                                        <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/pizza.png"
                                            alt="sample" />
                                        <div class="item_badge">
                                            <span class="badge_btext">20%</span>
                                            <span class="badge_stext">OFF</span>
                                        </div>
                                    </div>
                                    <div class="fadeUp fade-slow item_details">
                                        <h4 class="item_name">Delicious Food</h4>
                                        <p class="item_info">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                            eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                        <div class="item_link_box">
                                            <a href="#reservation" class="item_link page-scroll">Make Reservation</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="item">
                                <div class="carousel-caption">
                                    <div class="fadeUp item_img">
                                        <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/tortilla.png"
                                            alt="sample" />
                                        <div class="item_badge">
                                            <span class="badge_btext">20%</span>
                                            <span class="badge_stext">OFF</span>
                                        </div>
                                    </div>
                                    <div class="fadeUp fade-slow item_details">
                                        <h4 class="item_name">Delicious Food</h4>
                                        <p class="item_info">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                            eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                        <div class="item_link_box">
                                            <a href="#reservation" class="item_link page-scroll">Make Reservation</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="item">
                                <div class="carousel-caption">
                                    <div class="fadeUp item_img">
                                        <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/burger.png"
                                            alt="sample" />
                                        <div class="item_badge">
                                            <span class="badge_btext">20%</span>
                                            <span class="badge_stext">OFF</span>
                                        </div>
                                    </div>
                                    <div class="fadeUp fade-slow item_details">
                                        <h4 class="item_name">Delicious Food</h4>
                                        <p class="item_info">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                            eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                        <div class="item_link_box">
                                            <a href="#reservation" class="item_link page-scroll">Make Reservation</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="item">
                                <div class="carousel-caption">
                                    <div class="fadeUp item_img">
                                        <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/pizza.png"
                                            alt="sample" />
                                        <div class="item_badge">
                                            <span class="badge_btext">20%</span>
                                            <span class="badge_stext">OFF</span>
                                        </div>
                                    </div>
                                    <div class="fadeUp fade-slow item_details">
                                        <h4 class="item_name">Delicious Food</h4>
                                        <p class="item_info">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                            eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                        <div class="item_link_box">
                                            <a href="#reservation" class="item_link page-scroll">Make Reservation</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="item">
                                <div class="carousel-caption">
                                    <div class="fadeUp item_img">
                                        <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/burger.png"
                                            alt="sample" />
                                        <div class="item_badge">
                                            <span class="badge_btext">20%</span>
                                            <span class="badge_stext">OFF</span>
                                        </div>
                                    </div>
                                    <div class="fadeUp fade-slow item_details">
                                        <h4 class="item_name">Delicious Food</h4>
                                        <p class="item_info">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
                                            eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                        <div class="item_link_box">
                                            <a href="#reservation" class="item_link page-scroll">Make Reservation</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.container -->
                </div>
            <?php endif; ?>
            <!-- END carousel -->

            <!-- BEGIN navigation -->
            <div class="navigation">

                <div class="navbar-container" <?= $isPaginaDetalhesProduto ? '' : 'data-spy="affix" data-offset-top="400"'; ?>>
                    <div class="container">

                        <div class="navbar_top hidden-xs">
                            <div class="top_addr">
                                <span><i class="fa fa-map-marker" aria-hidden="true"></i> <?= esc($enderecoContato); ?></span>
                                <span><i class="fa fa-phone" aria-hidden="true"></i> <?= esc($telefoneContato); ?></span>
                                <?php if ($expedienteHoje->situacao == false): ?>
                                    <span><i class="fa fa-lock" aria-hidden="true"></i> HOJE ESTAMOS FECHADO </span>
                                <?php else: ?>
                                    <span><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo esc($expedienteHoje->abertura); ?> - <?php echo esc($expedienteHoje->fechamento); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- /.navbar_top -->

                        <!-- BEGIN navbar -->
                        <nav class="navbar">
                            <div id="navbar_content">
                                <!-- Brand and toggle get grouped for better mobile display -->
                                <div class="navbar-header">
                                    <a class="navbar-brand site-brand" href="<?php echo site_url('/'); ?>" aria-label="Gula Lanches">
                                        <img src="<?php echo site_url('web/'); ?>src/assets/img/logo.png" alt="logo" />
                                        <span class="site-brand-text">Gula Lanches</span>
                                    </a>
                                    <?php if ($isPaginaPrincipal || $isPaginaConta): ?>
                                        <div class="mobile-auth-opposite">
                                            <?php if (usuario_logado()): ?>
                                                <a href="<?php echo site_url('conta'); ?>" class="mobile-auth-link">Minha conta</a>
                                                <a href="<?php echo site_url('login/logout'); ?>" class="mobile-auth-link mobile-auth-link--danger">Sair</a>
                                            <?php else: ?>
                                                <a href="<?php echo site_url('login'); ?>" class="mobile-auth-link">Entrar</a>
                                                <a href="<?php echo site_url('registrar'); ?>" class="mobile-auth-link">Registre-se</a>
                                            <?php endif ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="mobile-header-actions">
                                        <?php if (session()->has('carrinho') && count(session()->get('carrinho')) > 0): ?>
                                            <a href="<?php echo site_url('carrinho'); ?>" class="mobile-cart-link" aria-label="Carrinho">
                                                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                                <span class="mobile-cart-count"><?php echo count(session()->get('carrinho')); ?></span>
                                            </a>
                                        <?php endif ?>
                                        <a href="#cd-nav" class="cd-nav-trigger right_menu_icon" aria-label="Abrir menu">
                                            <span><i class="fa fa-bars" aria-hidden="true"></i></span>
                                        </a>
                                    </div>
                                </div>

                                <!-- Collect the nav links, forms, and other content for toggling -->
                                <div class="collapse navbar-collapse" id="navbar">
                                    <div class="navbar-right">
                                        <ul class="nav navbar-nav">
                                            <li><?php if ($isPaginaPrincipal): ?><a class="page-scroll" href="#header">Home</a><?php else: ?><a href="<?php echo site_url('/'); ?>">Home</a><?php endif; ?></li>
                                            <?php if ($isPaginaPrincipal): ?><li><a class="page-scroll" href="#gallery">Galeria</a></li>
                                                <li><a class="page-scroll" href="#footer">Contato</a></li><?php else: ?><li><a href="<?php echo site_url('/'); ?>#gallery">Galeria</a></li>
                                                <li><a href="<?php echo site_url('/'); ?>#footer">Contato</a></li><?php endif; ?>

                                            <?php if (session()->has('carrinho') && count(session()->get('carrinho')) > 0): ?>
                                                <li><a class="page-scroll" href="<?php echo site_url('carrinho'); ?>">
                                                        <i class="fa fa-shopping-cart fa fa-2x" aria-hidden="true"></i>
                                                        <span style="font-size: 25px !important;">
                                                            <?php echo count(session()->get('carrinho')); ?>
                                                        </span>
                                                    </a>
                                                </li>
                                            <?php endif ?>
                                            <?php if (usuario_logado()): ?>
                                                <li><a class="page-scroll" href="<?php echo site_url('conta'); ?>">Minha conta</a></li>
                                                <li><a class="page-scroll" href="<?php echo site_url('login/logout'); ?>">Sair</a></li>

                                            <?php else: ?>
                                                <li><a class="page-scroll" href="<?php echo site_url('login'); ?>">Entrar</a></li>
                                                <li><a class="page-scroll" href="<?php echo site_url('registrar'); ?>">Registre-se</a></li>
                                            <?php endif ?>

                                        </ul>
                                    </div>
                                </div>
                                <!-- /.navbar-collapse -->
                            </div>
                        </nav>
                    </div>
                    <!-- END navbar -->
                </div>
                <!-- /.navbar-container -->
            </div>
            <!-- END navigation -->

        </header>
        <!-- End header -->

        <div class="container">
            <?php if (session()->has('sucesso')): ?>
                <div class="alert alert-success alert-dismissible fade in" role="alert">
                    <strong>Perfeito!</strong>
                    <?= session('sucesso'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif ?>
            <?php if (session()->has('info')): ?>
                <div class="alert alert-info alert-dismissible fade in" role="alert">
                    <strong>Informação!</strong>
                    <?= session('info'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif ?>
            <?php if (session()->has('atencao')): ?>
                <div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <strong>Atenção!</strong>
                    <?= session('atencao'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif ?>
            <?php if (session()->has(key: 'fraude')): ?>
                <div class="alert alert-warning alert-dismissible fade in" role="alert">
                    <strong>Atenção!</strong>
                    <?= session('fraude'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif ?>
            <?php if (session()->has('error')): ?>
                <div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <strong>Erro!</strong>
                    <?= session('error'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif ?>
        </div>

        <?= $this->renderSection('conteudo') ?>

        <?php if ($isPaginaPrincipal): ?>
            <!-- Contact -->
            <div class="section" id="contact">
                <div id="googleMap"></div>
            </div>
        <?php endif; ?>

        <?php if ($isPaginaPrincipal): ?>
            <!--  Begin Footer  -->
            <footer id="footer">
                <div class="footer_pos">
                    <div class="container">
                        <div class="footer_content">
                            <div class="row">
                                <div class="col-sm-6 col-md-4">
                                    <h4 class="footer_ttl footer_ttl_padd">Sobre Nós</h4>
                                    <p class="footer_txt">Desde 1999, a Gula Lanches une tradição e excelência. Com ingredientes frescos diários e receitas originais que atravessam gerações, servimos comida de verdade, feita com o coração. Hoje, levamos esse mesmo sabor nostálgico do nosso clássico balcão direto para a sua casa através do nosso delivery. </p>
                                </div>
                                <div class="col-sm-6 col-md-5">
                                    <?php $expedientes = expedientes(); ?>
                                    <h4 class="footer_ttl footer_ttl_padd">Nossos expedientes</h4>
                                    <div class="footer_border">
                                        <?php foreach ($expedientes as $dia): ?>
                                            <div class="week_row clearfix">
                                                <div class="week_day"><?php echo esc($dia->dia_descricao); ?></div>
                                                <?php if ($dia->situacao == false): ?>
                                                    <div class="week_time text-right">Fechado </div>
                                                <?php else: ?>
                                                    <div class="week_time text-right">
                                                        <?php echo esc(date('H:i', strtotime($dia->abertura))); ?>
                                                        -
                                                        <?php echo esc(date('H:i', strtotime($dia->fechamento))); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-3">
                                    <h4 class="footer_ttl footer_ttl_padd">Contato</h4>
                                    <div class="footer_border footer_border-contato">
                                        <div class="footer_cnt">
                                            <i class="fa fa-map-marker"></i>
                                            <span><?= esc($enderecoContato); ?></span>
                                        </div>
                                        <div class="footer_cnt">
                                            <i class="fa fa-phone"></i>
                                            <span><?= esc($telefoneContato); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="copyright">
                    <div class="container">
                        <div class="site-footer">
                            <div class="footer-content">
                                <span class="dev-credits">
                                    Code by
                                    <i class="fa-solid fa-star star-icon"></i>
                                    <a href="https://github.com/JulioDev10" target="_blank" class="dev-link"
                                        title="Visitar GitHub">
                                        JulioDev10
                                    </a>
                                </span>

                                <span class="separator">|</span>

                                <div class="footer-right">
                                    <a href="https://wa.me/5535998407525" target="_blank" class="social-link whatsapp"
                                        title="WhatsApp">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </a>

                                    <a href="https://github.com/JulioDev10" target="_blank" class="social-link github"
                                        title="GitHub">
                                        <i class="fa-brands fa-github"></i>
                                    </a>

                                    <a href="https://discord.com/users/1375261099724640306" target="_blank"
                                        class="social-link discord" title="Discord">
                                        <i class="fa-brands fa-discord"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="footer-copyright">
                                &copy; <?= date('Y') ?> Todos os direitos reservados.
                            </div>
                        </div>
                    </div>
                </div>
    </div>
    </footer>
<?php elseif (!$isPaginaCustomizarProduto): ?>
    <!--  Begin Footer  -->
    <footer id="footer">
        <div class="container">
            <?= view('footer') ?>
        </div>
    </footer>
<?php endif; ?>

<!-- End Footer -->

</div>
<!-- END body-wrapper -->


<!-- START mobile right burger menu -->

<nav class="cd-nav-container right_menu" id="cd-nav">
    <div class="header__open_menu">
        <a href="<?php echo site_url('/'); ?>" class="rmenu_logo" title="Food delivery">
            <img src="<?php echo site_url('web/'); ?>src/assets/img/logo.png" alt="logo" /><span class="site-brand-text">Gula Lanches</span>
        </a>
    </div>
    <ul class="rmenu_list">
        <li><?php if ($isPaginaPrincipal): ?><a class="page-scroll" href="#header">Home</a><?php else: ?><a href="<?php echo site_url('/'); ?>">Home</a><?php endif; ?></li>
        <?php if ($isPaginaPrincipal): ?><li><a class="page-scroll" href="#menu">Menus</a></li>
            <li><a class="page-scroll" href="#gallery">Galeria</a></li>
            <li><a class="page-scroll" href="#footer">Contato</a></li><?php else: ?><li><a href="<?php echo site_url('/'); ?>#menu">Menus</a></li>
            <li><a href="<?php echo site_url('/'); ?>#gallery">Galeria</a></li>
            <li><a href="<?php echo site_url('/'); ?>#footer">Contato</a></li><?php endif; ?>
        <?php if (session()->has('carrinho') && count(session()->get('carrinho')) > 0): ?>
            <li>
                <a href="<?php echo site_url('carrinho'); ?>">
                    <i class="fa fa-shopping-cart fa-lg" aria-hidden="true"></i>
                    <span style="font-size: 18px; margin-left: 6px;">
                        <?php echo count(session()->get('carrinho')); ?>
                    </span>
                </a>
            </li>
        <?php endif ?>
    </ul>
    <div class="right_menu_addr top_addr">
        <span><i class="fa fa-map-marker" aria-hidden="true"></i> <?= esc($enderecoContato); ?></span>
        <span><i class="fa fa-phone" aria-hidden="true"></i> <?= esc($telefoneContato); ?></span>
        <?php if ($expedienteHoje->situacao == false): ?>
            <span><i class="fa fa-lock" aria-hidden="true"></i> HOJE ESTAMOS FECHADO </span>
        <?php else: ?>
            <span><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo esc($expedienteHoje->abertura); ?> - <?php echo esc($expedienteHoje->fechamento); ?></span>
        <?php endif; ?>
    </div>
</nav>

<div class="cd-overlay"></div>
<!-- /.cd-overlay -->


<!-- END mobile right burger menu -->

<!-- JavaScript -->
<script src="<?php echo site_url('web/'); ?>src/assets/js/jquery-2.1.1.min.js"></script>
<script src="<?php echo site_url('web/'); ?>src/assets/js/bootstrap.min.js"></script>
<script src="<?php echo site_url('web/'); ?>src/assets/js/jquery.mousewheel.min.js"></script>
<script src="<?php echo site_url('web/'); ?>src/assets/js/jquery.easing.min.js"></script>
<script src="<?php echo site_url('web/'); ?>src/assets/js/scrolling-nav.js"></script>
<script src="<?php echo site_url('web/'); ?>src/assets/js/aos.js"></script>
<script src="<?php echo site_url('web/'); ?>src/assets/js/slick.min.js"></script>
<script src="<?php echo site_url('web/'); ?>src/assets/js/jquery.touchSwipe.min.js"></script>
<script src="<?php echo site_url('web/'); ?>src/assets/js/moment.js"></script>
<script src="<?php echo site_url('web/'); ?>src/assets/js/bootstrap-datepicker.js"></script>
<script src="<?php echo site_url('web/'); ?>src/assets/js/bootstrap-datetimepicker.js"></script>
<script src="<?php echo site_url('web/'); ?>src/assets/js/jquery.fancybox.js"></script>
<script src="<?php echo site_url('web/'); ?>src/assets/js/loadMoreResults.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="<?php echo site_url('web/'); ?>src/assets/js/main.js"></script>

<!-- Essa section redenderizáos estilos de cada view para ester esse layout-->
<?= $this->renderSection('scripts') ?>

</body>

</html>