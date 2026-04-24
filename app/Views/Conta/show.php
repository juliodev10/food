<?= $this->extend('layout/principal_web'); ?>

<?= $this->section('titulo'); ?>
<?= $titulo; ?>
<?= $this->endSection() ?>

<?= $this->section('estilos'); ?>
<!-- Aqui enviamos para o template principal os estilos -->
<link href="<?php echo site_url('web/'); ?>src/assets/css/conta.css" type="text/css" rel="stylesheet" />
<style>
    .produto-detalhes {
        margin: 2rem 0;
    }

    .produto-detalhes .produto-imagem {
        width: 100%;
        max-width: 460px;
        border-radius: 8px;
        display: block;
    }

    .produto-detalhes .produto-titulo {
        margin-bottom: 1rem;
    }

    .produto-detalhes .produto-ingredientes {
        font-size: 15px;
        line-height: 1.6;
    }

    .preco-padrao {
        font-size: 18px;
        color: #990100;
        font-family: 'Montserrat-Bold';
    }

    .quantidade-input {
        width: 80px;
        max-width: 100%;
        text-align: center;
    }

    .quantidade-input[type=number] {
        -moz-appearance: auto;
        appearance: auto;
    }

    .quantidade-input[type=number]::-webkit-outer-spin-button,
    .quantidade-input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: inner-spin-button;
        opacity: 1;
        margin: 0;
    }

    .quantidade-control {
        display: flex;
        align-items: stretch;
        max-width: 125px;
    }

    .quantidade-acoes {
        display: flex;
        flex-direction: column;
        margin-left: 4px;
    }

    .quantidade-acoes .btn {
        padding: 2px 6px;
        line-height: 1;
        border-radius: 3px;
    }

    .quantidade-acoes .btn:first-child {
        margin-bottom: 2px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('conteudo'); ?>
<div class="container section" id="menu" data-aos="fade-up" style="margin-top: 3rem; min-height: 300px;">
    <?php echo $this->include('Conta/sidebar'); ?>
    <div class="row">
        <div class="container" style="margin-top: 2em;">
            <h2 class="section-title pull-left"><?php echo esc($titulo) ?></h2>
        </div>
        <div class="col-md-6">
            <div class="panel panel-info">
                <div class="panel-body">
                    <dl>
                        <dt>Nome completo:</dt>
                        <dd><?php echo esc($usuario->nome) ?></dd>
                        <hr>
                        <dt>Email:</dt>
                        <dd><?php echo esc($usuario->email) ?></dd>
                        <hr>
                        <dt>Telefone:</dt>
                        <dd><?php echo esc($usuario->telefone) ?></dd>
                        <hr>
                        <dt>Cliente desde:</dt>
                        <dd><?php echo $usuario->criado_em->humanize(); ?></dd>
                    </dl>
                </div>
                <div class="panel-footer">
                    <a href="<?php echo site_url('conta/editar'); ?>" class="btn btn-primary">Editar</a>
                    <a href="<?php echo site_url('conta/editarsenha'); ?>" class="btn btn-info">Alterar senha</a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<?= $this->endSection() ?>

<!-- Aqui enviamos para o template principal os scripts -->
<?= $this->section('scripts'); ?>
<script>
    function openNav() {
        document.getElementById("mySidebar").style.width = "250px";
        document.getElementById("main").style.marginLeft = "250px";
    }

    /* Set the width of the sidebar to 0 and the left margin of the page content to 0 */
    function closeNav() {
        document.getElementById("mySidebar").style.width = "0";
        document.getElementById("main").style.marginLeft = "0";
    }
</script>

<?= $this->endSection() ?>
<!-- Begin Sections-->