<?= $this->extend('Admin/layout/principal'); ?>


<?= $this->section('titulo'); ?>
<?= $titulo; ?>
<?= $this->endSection() ?>

<?= $this->section('estilos'); ?>
<!-- Aqui enviamos para o template principal os estilos -->
<style>
    .top-entregador-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .top-entregador-imagem {
        width: 52px;
        height: 52px;
        max-width: 100%;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background-color: #f8f9fa;
        flex-shrink: 0;
    }

    .top-entregador-nome {
        flex: 1;
        min-width: 0;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('conteudo'); ?>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body dashboard-tabs p-0">
                <div class="tab-content py-0 px-0">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                        <div class="d-flex flex-wrap justify-content-xl-between">
                            <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                                <i class="mdi mdi-currency-usd icon-lg mr-3 text-primary"></i>
                                <div class="d-flex flex-column justify-content-around">
                                    <small class="mb-1 text-muted">Pedidos entregues
                                        <span class="badge badge-pill badge-primary"><?= $valorPedidosEntregues->total ?></span>
                                    </small>
                                    <h5 class="mr-2 mb-0">R$<?= number_format($valorPedidosEntregues->valor_pedido, 2, ',', '.') ?></h5>
                                </div>
                            </div>
                            <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                                <i class="mdi mdi-currency-usd mr-3 icon-lg text-danger"></i>
                                <div class="d-flex flex-column justify-content-around">
                                    <small class="mb-1 text-muted">Pedidos cancelados
                                        <span class="badge badge-pill badge-danger"><?= $valorPedidosCancelados->total ?></span>
                                    </small>
                                    <h5 class="mr-2 mb-0">R$<?= number_format($valorPedidosCancelados->valor_pedido, 2, ',', '.') ?></h5>
                                </div>
                            </div>
                            <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                                <i class="mdi mdi-account-multiple mr-3 icon-lg text-success"></i>
                                <div class="d-flex flex-column justify-content-around">
                                    <small class="mb-1 text-muted">Clientes ativos</small>
                                    <h5 class="mr-2 mb-0"><?= $totalClientesAtivos ?></h5>
                                </div>
                            </div>
                            <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                                <i class="mdi mdi-motorbike mr-3 icon-lg text-warning"></i>
                                <div class="d-flex flex-column justify-content-around">
                                    <small class="mb-1 text-muted">Entregadores ativos</small>
                                    <h5 class="mr-2 mb-0"><?= $totalEntregadoresAtivos ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body dashboard-tabs p-0">
                <?php if (!$empresaAbertaAgora): ?>
                    <h5 class="text-danger">O expediente de hoje está fechado.<?php echo date('d/m/Y H:i:s'); ?></h5>
                <?php else: ?>
                    <h6 class="pt-3 pe-3">
                        <i class="mdi mdi-cart-plus"></i> Novos Pedidos
                    </h6>
                    <hr class="border-primary">
                    <div id="atualiza">
                        <?php if (empty($novosPedidos)): ?>
                            <h5 class="text-info">Nenhum novo pedido encontrado.<?php echo date('d/m/Y H:i:s'); ?></h5>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>Código do pedido</th>
                                            <th>Valor</th>
                                            <th>Data do pedido</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($novosPedidos as $pedido): ?>
                                            <tr>
                                                <td>
                                                    <a
                                                        href="<?= site_url('admin/pedidos/show/' . $pedido->codigo); ?>"><?= $pedido->codigo; ?></a>
                                                </td>
                                                <td>R$&nbsp;<?= esc(number_format($pedido->valor_total, 2, ',', '.')); ?></td>
                                                <td><?= esc($pedido->criado_em->humanize()); ?></td>
                                                <td>
                                                    <?php if ($pedido->deletado_em !== null): ?>
                                                        <a href="<?= site_url('admin/pedidos/desfazerExclusao/' . $pedido->codigo); ?>"
                                                            class="btn btn-info btn-sm btn-icon-text ml-2">
                                                            <i class="mdi mdi-undo btn-icon-prepend"></i> Desfazer</a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div><!--fim div atualiza-->
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <p class="card-title">Top Clientes</p>
                <ul class="list-arrow">
                    <?php if (!isset($clientesMaisAssiduos)): ?>
                        <p class="card-title text-info">Não há dados pra exibir no momento</p>
                    <?php else: ?>
                        <?php foreach ($clientesMaisAssiduos as $cliente): ?>
                            <li class="mb-2"><?= esc($cliente->nome); ?> <span class="badge badge-pill badge-success float-right"><?= esc($cliente->pedidos); ?></span></li>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <p class="card-title">Produtos + vendidos</p>
                <ul class="list-arrow">
                    <?php if (!isset($produtosMaisVendidos)): ?>
                        <p class="card-title text-info">Não há dados pra exibir no momento</p>
                    <?php else: ?>
                        <?php foreach ($produtosMaisVendidos as $produto): ?>
                            <li class="mb-2"><?= word_limiter($produto->produto, 5); ?> <span class="badge badge-pill badge-primary float-right"><?= esc($produto->quantidade); ?></span></li>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <p class="card-title">Top Entregadores</p>
                <ul class="list-unstyled">
                    <?php if (!isset($entregadoresMaisAssiduos)): ?>
                        <p class="card-title text-info">Não há dados pra exibir no momento</p>
                    <?php else: ?>
                        <?php foreach ($entregadoresMaisAssiduos as $entregador): ?>
                            <li class="mb-2 top-entregador-item">
                                <img class="top-entregador-imagem" src="<?php echo site_url("admin/entregadores/imagem/$entregador->imagem") ?>" alt="Imagem do entregador <?= esc($entregador->nome); ?>">
                                <span class="top-entregador-nome"><?= esc($entregador->nome); ?></span>
                                <span class="badge badge-pill badge-warning"><?= esc($entregador->entregas); ?></span>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </ul>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts'); ?>
<script>
    function atualiza() {
        $('#atualiza').load('<?php echo site_url('admin/home'); ?>' + ' #atualiza');
    }

    setInterval(atualiza, 120000);
</script>

<?= $this->endSection() ?>