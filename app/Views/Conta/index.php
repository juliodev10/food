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
        <div class="col-md-12">
            <?php if (!isset($pedidos)): ?>
                <h4 class="text-info">Nessa área aparecerá o seu histórico de pedidos realizados.</h4>
            <?php else: ?>
                <?php foreach ($pedidos as $key => $pedido): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Pedido #<?php echo $pedido->id; ?></h5>
                            <div class="panel-group">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <!-- Trigger -->
                                            <a data-toggle="collapse" href="#collapse<?php echo $key; ?>">Pedido <?php echo esc($pedido->codigo); ?> - Realizado <?php echo esc($pedido->criado_em->humanize()); ?></a>
                                        </h4>
                                    </div>
                                    <!-- Target -->
                                    <div id="collapse<?php echo $key; ?>" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <h5>Situação do pedido: <?php echo $pedido->exibeSituacaoDoPedido(); ?></h5>
                                            <ul class="list-group">
                                                <?php $produtos = unserialize($pedido->produtos); ?>
                                                <?php foreach ($produtos as $produto) : ?>
                                                    <li class="list-group-item">
                                                        <div>
                                                            <h4><?php echo ellipsize($produto['nome'], 60); ?></h4>
                                                            <p class="text-muted">
                                                                Preço: R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?> | Quantidade: <?php echo $produto['quantidade']; ?>
                                                            </p>
                                                        </div>
                                                    </li>
                                                <?php endforeach; ?>
                                                <li class="list-group-item">
                                                    <span>Total de produtos</span>
                                                    <strong>R$ <?php echo number_format($pedido->total, 2, ',', '.'); ?></strong>
                                                </li>
                                                <li class="list-group-item">
                                                    <span>Taxa de entrega</span>
                                                    <strong id="valor_entrega">R$ <?php echo number_format($pedido->valor_entrega, 2, ',', '.'); ?></strong>
                                                </li>
                                                <li class="list-group-item">
                                                    <span>Valor do pedido</span>
                                                    <strong id="total"> R$ <?php echo number_format($pedido->total + $pedido->valor_entrega, 2, ',', '.'); ?></strong>
                                                </li>
                                                <li class="list-group-item">
                                                    <span>Endereço de entrega</span>
                                                    <strong id="endereco"><?php echo esc($pedido->endereco_entrega); ?></strong>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
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