<?= $this->extend('layout/principal_web'); ?>


<?= $this->section('titulo'); ?>
<?= $titulo; ?>
<?= $this->endSection() ?>

<?= $this->section('estilos'); ?>
<!-- Aqui enviamos para o template principal os estilos -->
<?= $this->endSection() ?>

<?= $this->section('conteudo'); ?>

<!-- Begin Sections-->

<!--    About Us    -->
<div class="container section" id="sobre-nos" style="margin-top: 1em">
    <div class="col-sm-12 d-flex flex-xs-column">
        <div class="col-sm-6 d-flex align-items-stretch padd_lr" data-aos="fade-up">
            <div class="content">
                <h1 class="section-title title_sty1">Sobre Nós</h1>
                <p class="short"><strong>Nossa História:</strong> O sabor que atravessa gerações
                    Em 2001, a nossa chapa esquentou pela primeira vez. O que começou como um modesto balcão de esquina,
                    acabou se tornando parte da história da nossa cidade.

                    Na Gula Lanches, nós acreditamos que algumas coisas não devem mudar. O nosso segredo sempre
                    foi a simplicidade feita com excelência. O pão continua sendo entregue fresco toda madrugada pela
                    mesma padaria de 27 anos atrás. Nossa famosa maionese verde ainda segue a receita original, batida
                    fresca todos os dias. E aquele hambúrguer suculento?

                    Ao longo das décadas, vimos clientes que vinham comer aqui quando crianças agora trazerem seus
                    próprios filhos para provar o "clássico da casa". Nós acompanhamos o crescimento do bairro,
                    celebramos vitórias do time da cidade com nossos clientes e ouvimos muitas histórias apoiadas no
                    nosso balcão.

                    Sabemos que o mundo mudou e que a rotina está mais corrida. Por isso, mesmo mantendo a nossa
                    essência raiz e o ambiente que você já conhece, hoje também levamos aquele mesmo sabor nostálgico —
                    quentinho e impecável — direto para a sua casa através da nossa plataforma de delivery.

                    Seja sentado na sua banqueta favorita ou recebendo nosso pacote na sua porta, a experiência é a
                    mesma: comida de verdade, feita com o coração.

                    Obrigado por fazer parte da nossa mesa e da nossa história.</p>
            </div>
        </div>
        <div class="col-sm-6 img text-center padd_lr0" data-aos="fade-down">
            <div class="border_on">
                <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/about-us.jpg" alt="sample"
                    class="about_img" />
            </div>
        </div>
    </div>
</div>

<!--    Menus   -->
<div class="container section" id="menu" data-aos="fade-up" style="margin-top: 1em">
    <h1 class="section-title">Conheça as nossas delícias</h1>

    <!--    Menus filter    -->
    <div class="menu_filter text-center">
        <ul class="list-unstyled list-inline d-inline-block">
            <li class="item active">
                <a href="javascript:;" class="filter-button" data-filter="burger">Burger</a>
            </li>
            <li class="item">
                <a href="javascript:;" class="filter-button" data-filter="pizza">Pizza</a>
            </li>
            <li class="item">
                <a href="javascript:;" class="filter-button" data-filter="salad">Salad</a>
            </li>
            <li class="item">
                <a href="javascript:;" class="filter-button" data-filter="frices">Frices</a>
            </li>
            <li class="item">
                <a href="javascript:;" class="filter-button" data-filter="drinks">Drinks</a>
            </li>
        </ul>
    </div>

    <!--    Menus items     -->
    <div id="menu_items">

        <div class="filtr-item image filter burger active">
            <div class="row">
                <div class="col-sm-6">
                    <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/food-1.jpg" class="block fancybox"
                        data-fancybox-group="fancybox">
                        <div class="content">
                            <div class="filter_item_img">
                                <i class="fa fa-search-plus"></i>
                                <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/food-1.jpg"
                                    alt="sample" />
                            </div>
                            <div class="info">
                                <div class="name">Margherita</div>
                                <div class="short">Classic marinara sauce, authentic pepperoni</div>
                                <span class="filter_item_price">$10.00</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/food-2.jpg" class="block fancybox"
                        data-fancybox-group="fancybox">
                        <div class="content">
                            <div class="filter_item_img">
                                <i class="fa fa-search-plus"></i>
                                <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/food-2.jpg"
                                    alt="sample" />
                            </div>
                            <div class="info">
                                <div class="name">Greece</div>
                                <div class="short">Classic marinara sauce, authentic pepperoni</div>
                                <span class="filter_item_price">$7.00</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/food-3.jpg" class="block fancybox"
                        data-fancybox-group="fancybox">
                        <div class="content">
                            <div class="filter_item_img">
                                <i class="fa fa-search-plus"></i>
                                <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/food-3.jpg"
                                    alt="sample" />
                            </div>
                            <div class="info">
                                <div class="name">Pepperoni</div>
                                <div class="short">Classic marinara sauce, authentic pepperoni</div>
                                <span class="filter_item_price">$8.50</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/food-4.jpg" class="block fancybox"
                        data-fancybox-group="fancybox">
                        <div class="content">
                            <div class="filter_item_img">
                                <i class="fa fa-search-plus"></i>
                                <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/food-4.jpg"
                                    alt="sample" />
                            </div>
                            <div class="info">
                                <div class="name">Chicken lovers</div>
                                <div class="short">Classic marinara sauce, authentic pepperoni</div>
                                <span class="filter_item_price">$8.00</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/food-5.jpg" class="block fancybox"
                        data-fancybox-group="fancybox">
                        <div class="content">
                            <div class="filter_item_img">
                                <i class="fa fa-search-plus"></i>
                                <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/food-5.jpg"
                                    alt="sample" />
                            </div>
                            <div class="info">
                                <div class="name">Italiano</div>
                                <div class="short">Classic marinara sauce, authentic pepperoni</div>
                                <span class="filter_item_price">$11.00</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/food-6.jpg" class="block fancybox"
                        data-fancybox-group="fancybox">
                        <div class="content">
                            <div class="filter_item_img">
                                <i class="fa fa-search-plus"></i>
                                <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/food-6.jpg"
                                    alt="sample" />
                            </div>
                            <div class="info">
                                <div class="name">Pepper beef</div>
                                <div class="short">Classic marinara sauce, authentic pepperoni</div>
                                <span class="filter_item_price">$9.00</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/food-7.jpg" class="block fancybox"
                        data-fancybox-group="fancybox">
                        <div class="content">
                            <div class="filter_item_img">
                                <i class="fa fa-search-plus"></i>
                                <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/food-7.jpg"
                                    alt="sample" />
                            </div>
                            <div class="info">
                                <div class="name">Hawai</div>
                                <div class="short">Classic marinara sauce, authentic pepperoni</div>
                                <span class="filter_item_price">$11.00</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/food-8.jpg" class="block fancybox"
                        data-fancybox-group="fancybox">
                        <div class="content">
                            <div class="filter_item_img">
                                <i class="fa fa-search-plus"></i>
                                <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/food-8.jpg"
                                    alt="sample" />
                            </div>
                            <div class="info">
                                <div class="name">Caesar</div>
                                <div class="short">Classic marinara sauce, authentic pepperoni</div>
                                <span class="filter_item_price">$9.00</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="filtr-item image filter pizza">
            <div class="row">
                <div class="col-sm-6">
                    <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/food-6.jpg" class="block fancybox"
                        data-fancybox-group="fancybox">
                        <div class="content">
                            <div class="filter_item_img">
                                <i class="fa fa-search-plus"></i>
                                <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/food-6.jpg"
                                    alt="sample" />
                            </div>
                            <div class="info">
                                <div class="name">Pepper beef</div>
                                <div class="short">Classic marinara sauce, authentic pepperoni</div>
                                <span class="filter_item_price">$9.00</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/food-8.jpg" class="block fancybox"
                        data-fancybox-group="fancybox">
                        <div class="content">
                            <div class="filter_item_img">
                                <i class="fa fa-search-plus"></i>
                                <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/food-8.jpg"
                                    alt="sample" />
                            </div>
                            <div class="info">
                                <div class="name">Caesar</div>
                                <div class="short">Classic marinara sauce, authentic pepperoni</div>
                                <span class="filter_item_price">$9.00</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/food-7.jpg" class="block fancybox"
                        data-fancybox-group="fancybox">
                        <div class="content">
                            <div class="filter_item_img">
                                <i class="fa fa-search-plus"></i>
                                <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/food-7.jpg"
                                    alt="sample" />
                            </div>
                            <div class="info">
                                <div class="name">Hawai</div>
                                <div class="short">Classic marinara sauce, authentic pepperoni</div>
                                <span class="filter_item_price">$11.00</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/food-5.jpg" class="block fancybox"
                        data-fancybox-group="fancybox">
                        <div class="content">
                            <div class="filter_item_img">
                                <i class="fa fa-search-plus"></i>
                                <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/food-5.jpg"
                                    alt="sample" />
                            </div>
                            <div class="info">
                                <div class="name">Italiano</div>
                                <div class="short">Classic marinara sauce, authentic pepperoni</div>
                                <span class="filter_item_price">$11.00</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="filtr-item image filter salad">
            <div class="row">
                <div class="col-sm-6">
                    <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/food-3.jpg" class="block fancybox"
                        data-fancybox-group="fancybox">
                        <div class="content">
                            <div class="filter_item_img">
                                <i class="fa fa-search-plus"></i>
                                <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/food-3.jpg"
                                    alt="sample" />
                            </div>
                            <div class="info">
                                <div class="name">Pepperoni</div>
                                <div class="short">Classic marinara sauce, authentic pepperoni</div>
                                <span class="filter_item_price">$8.50</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/food-2.jpg" class="block fancybox"
                        data-fancybox-group="fancybox">
                        <div class="content">
                            <div class="filter_item_img">
                                <i class="fa fa-search-plus"></i>
                                <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/food-2.jpg"
                                    alt="sample" />
                            </div>
                            <div class="info">
                                <div class="name">Greece</div>
                                <div class="short">Classic marinara sauce, authentic pepperoni</div>
                                <span class="filter_item_price">$7.00</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/food-4.jpg" class="block fancybox"
                        data-fancybox-group="fancybox">
                        <div class="content">
                            <div class="filter_item_img">
                                <i class="fa fa-search-plus"></i>
                                <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/food-4.jpg"
                                    alt="sample" />
                            </div>
                            <div class="info">
                                <div class="name">Chicken lovers</div>
                                <div class="short">Classic marinara sauce, authentic pepperoni</div>
                                <span class="filter_item_price">$8.00</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/food-1.jpg" class="block fancybox"
                        data-fancybox-group="fancybox">
                        <div class="content">
                            <div class="filter_item_img">
                                <i class="fa fa-search-plus"></i>
                                <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/food-1.jpg"
                                    alt="sample" />
                            </div>
                            <div class="info">
                                <div class="name">Margherita</div>
                                <div class="short">Classic marinara sauce, authentic pepperoni</div>
                                <span class="filter_item_price">$10.00</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="filtr-item image filter frices">
            <div class="row">
                <div class="col-sm-6">
                    <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/food-8.jpg" class="block fancybox"
                        data-fancybox-group="fancybox">
                        <div class="content">
                            <div class="filter_item_img">
                                <i class="fa fa-search-plus"></i>
                                <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/food-8.jpg"
                                    alt="sample" />
                            </div>
                            <div class="info">
                                <div class="name">Caesar</div>
                                <div class="short">Classic marinara sauce, authentic pepperoni</div>
                                <span class="filter_item_price">$9.00</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/food-7.jpg" class="block fancybox"
                        data-fancybox-group="fancybox">
                        <div class="content">
                            <div class="filter_item_img">
                                <i class="fa fa-search-plus"></i>
                                <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/food-7.jpg"
                                    alt="sample" />
                            </div>
                            <div class="info">
                                <div class="name">Hawai</div>
                                <div class="short">Classic marinara sauce, authentic pepperoni</div>
                                <span class="filter_item_price">$11.00</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/food-6.jpg" class="block fancybox"
                        data-fancybox-group="fancybox">
                        <div class="content">
                            <div class="filter_item_img">
                                <i class="fa fa-search-plus"></i>
                                <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/food-6.jpg"
                                    alt="sample" />
                            </div>
                            <div class="info">
                                <div class="name">Pepper beef</div>
                                <div class="short">Classic marinara sauce, authentic pepperoni</div>
                                <span class="filter_item_price">$9.00</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/food-2.jpg" class="block fancybox"
                        data-fancybox-group="fancybox">
                        <div class="content">
                            <div class="filter_item_img">
                                <i class="fa fa-search-plus"></i>
                                <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/food-2.jpg"
                                    alt="sample" />
                            </div>
                            <div class="info">
                                <div class="name">Greece</div>
                                <div class="short">Classic marinara sauce, authentic pepperoni</div>
                                <span class="filter_item_price">$7.00</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="filtr-item image filter drinks">
            <div class="row">
                <div class="col-sm-6">
                    <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/food-1.jpg" class="block fancybox"
                        data-fancybox-group="fancybox">
                        <div class="content">
                            <div class="filter_item_img">
                                <i class="fa fa-search-plus"></i>
                                <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/food-1.jpg"
                                    alt="sample" />
                            </div>
                            <div class="info">
                                <div class="name">Margherita</div>
                                <div class="short">Classic marinara sauce, authentic pepperoni</div>
                                <span class="filter_item_price">$10.00</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/food-5.jpg" class="block fancybox"
                        data-fancybox-group="fancybox">
                        <div class="content">
                            <div class="filter_item_img">
                                <i class="fa fa-search-plus"></i>
                                <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/food-5.jpg"
                                    alt="sample" />
                            </div>
                            <div class="info">
                                <div class="name">Italiano</div>
                                <div class="short">Classic marinara sauce, authentic pepperoni</div>
                                <span class="filter_item_price">$11.00</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/food-3.jpg" class="block fancybox"
                        data-fancybox-group="fancybox">
                        <div class="content">
                            <div class="filter_item_img">
                                <i class="fa fa-search-plus"></i>
                                <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/food-3.jpg"
                                    alt="sample" />
                            </div>
                            <div class="info">
                                <div class="name">Pepperoni</div>
                                <div class="short">Classic marinara sauce, authentic pepperoni</div>
                                <span class="filter_item_price">$8.50</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/food-4.jpg" class="block fancybox"
                        data-fancybox-group="fancybox">
                        <div class="content">
                            <div class="filter_item_img">
                                <i class="fa fa-search-plus"></i>
                                <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/food-4.jpg"
                                    alt="sample" />
                            </div>
                            <div class="info">
                                <div class="name">Chicken lovers</div>
                                <div class="short">Classic marinara sauce, authentic pepperoni</div>
                                <span class="filter_item_price">$8.00</span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="text-center">
            <!-- BEGIN pagination -->
            <ul class="pagination">
                <li class="active"><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">4</a></li>
            </ul>
            <!-- END pagination -->
        </div>

    </div>
</div>

<!--    Gallery    -->
<div class="container section" id="gallery" data-aos="fade-up">
    <div class="title-block">
        <h1 class="section-title">Gallery</h1>
    </div>
    <div id="photo_gallery" class="list1">
        <div class="row loadMore">
            <div class="col-sm-4 col-md-3 item">
                <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/gallery-1.jpg" class="block fancybox"
                    data-fancybox-group="fancybox">
                    <div class="content">
                        <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/gallery-1.jpg" alt="sample" />
                        <div class="zoom">
                            <span class="zoom_icon"><i class="fa fa-search-plus"></i></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-4 col-md-3 item">
                <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/gallery-2.jpg" class="block fancybox"
                    data-fancybox-group="fancybox">
                    <div class="content">
                        <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/gallery-2.jpg" alt="sample" />
                        <div class="zoom">
                            <span class="zoom_icon"><i class="fa fa-search-plus"></i></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-4 col-md-3 item">
                <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/gallery-3.jpg" class="block fancybox"
                    data-fancybox-group="fancybox">
                    <div class="content">
                        <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/gallery-3.jpg" alt="sample" />
                        <div class="zoom">
                            <span class="zoom_icon"><i class="fa fa-search-plus"></i></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-4 col-md-3 item">
                <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/gallery-4.jpg" class="block fancybox"
                    data-fancybox-group="fancybox">
                    <div class="content">
                        <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/gallery-4.jpg" alt="sample" />
                        <div class="zoom">
                            <span class="zoom_icon"><i class="fa fa-search-plus"></i></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-4 col-md-3 item">
                <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/gallery-5.jpg" class="block fancybox"
                    data-fancybox-group="fancybox">
                    <div class="content">
                        <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/gallery-5.jpg" alt="sample" />
                        <div class="zoom">
                            <span class="zoom_icon"><i class="fa fa-search-plus"></i></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-4 col-md-3 item">
                <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/gallery-6.jpg" class="block fancybox"
                    data-fancybox-group="fancybox">
                    <div class="content">
                        <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/gallery-6.jpg" alt="sample" />
                        <div class="zoom">
                            <span class="zoom_icon"><i class="fa fa-search-plus"></i></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-4 col-md-3 item">
                <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/gallery-7.jpg" class="block fancybox"
                    data-fancybox-group="fancybox">
                    <div class="content">
                        <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/gallery-7.jpg" alt="sample" />
                        <div class="zoom">
                            <span class="zoom_icon"><i class="fa fa-search-plus"></i></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-4 col-md-3 item">
                <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/gallery-8.jpg" class="block fancybox"
                    data-fancybox-group="fancybox">
                    <div class="content">
                        <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/gallery-8.jpg" alt="sample" />
                        <div class="zoom">
                            <span class="zoom_icon"><i class="fa fa-search-plus"></i></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-4 col-md-3 item">
                <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/gallery-1.jpg" class="block fancybox"
                    data-fancybox-group="fancybox">
                    <div class="content">
                        <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/gallery-1.jpg" alt="sample" />
                        <div class="zoom">
                            <span class="zoom_icon"><i class="fa fa-search-plus"></i></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-4 col-md-3 item">
                <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/gallery-2.jpg" class="block fancybox"
                    data-fancybox-group="fancybox">
                    <div class="content">
                        <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/gallery-2.jpg" alt="sample" />
                        <div class="zoom">
                            <span class="zoom_icon"><i class="fa fa-search-plus"></i></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-4 col-md-3 item">
                <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/gallery-3.jpg" class="block fancybox"
                    data-fancybox-group="fancybox">
                    <div class="content">
                        <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/gallery-3.jpg" alt="sample" />
                        <div class="zoom">
                            <span class="zoom_icon"><i class="fa fa-search-plus"></i></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-4 col-md-3 item">
                <a href="<?php echo site_url('web/'); ?>src/assets/img/photos/gallery-4.jpg" class="block fancybox"
                    data-fancybox-group="fancybox">
                    <div class="content">
                        <img src="<?php echo site_url('web/'); ?>src/assets/img/photos/gallery-4.jpg" alt="sample" />
                        <div class="zoom">
                            <span class="zoom_icon"><i class="fa fa-search-plus"></i></span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- End Sections -->

<?= $this->endSection() ?>

<!-- Aqui enviamos para o template principal os scripts -->
<?= $this->section('scripts'); ?>
<?= $this->endSection() ?>