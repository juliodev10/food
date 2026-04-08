<?= $this->extend('Admin/layout/principal'); ?>


<?= $this->section('titulo'); ?>
<?= $titulo; ?>
<?= $this->endSection() ?>

<?= $this->section('estilos'); ?>

<style>
    .ui-autocomplete {
        z-index: 2000;
    }

    @media (max-width: 767px) {
        .pedido-actions {
            justify-content: stretch;
            flex-wrap: wrap;
            gap: .4rem;
        }

        .pedido-actions .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 1 1 100%;
            width: 100%;
            margin-right: 0 !important;
            white-space: nowrap;
        }

        .pedido-actions .btn .btn-icon-prepend {
            margin-right: .35rem;
            margin-left: 0;
        }
    }
</style>

<?= $this->endSection() ?>

<?= $this->section('conteudo'); ?>

<div class="row">

    <div class="col-lg-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-header bg-primary pb-0 pt-4">
                <h4 class="card-title text-white">
                    <?= esc($titulo); ?>
                </h4>
            </div>
            <div class="card-body">
                <p class="card-text">
                    <span class="font-weight-bold">Situação:</span>
                    <?= $pedido->exibeSituacaoDoPedido(); ?>
                </p>
                <p class="card-text">
                    <span class="font-weight-bold">Criado:</span>
                    <?= esc($pedido->criado_em->humanize()); ?>
                </p>
                <p class="card-text">
                    <span class="font-weight-bold">Atualizado:</span>
                    <?= esc($pedido->atualizado_em->humanize()); ?>
                </p>
                <p class="card-text">
                    <span class="font-weight-bold">Forma de pagamento:</span>
                    <?= esc($pedido->forma_pagamento); ?>
                </p>
                <p class="card-text">
                    <span class="font-weight-bold">Valor dos produtos:</span>
                    R$: <?= esc(number_format($pedido->valor_produtos, 2, ',', '.')); ?>
                </p>
                <p class="card-text">
                    <span class="font-weight-bold">Valor de entrega:</span>
                    R$: <?= esc(number_format($pedido->valor_entrega, 2, ',', '.')); ?>
                </p>
                <p class="card-text">
                    <span class="font-weight-bold">Valor do pedido:</span>
                    R$: <?= esc(number_format($pedido->valor_pedido, 2, ',', '.')); ?>
                </p>
                <p class="card-text">
                    <span class="font-weight-bold">Endereço de entrega:</span>
                    <?= esc($pedido->endereco_entrega); ?>
                </p>
                <?php if ($pedido->entregador_id != null): ?>
                    <p class="card-text">
                        <span class="font-weight-bold">Entregador:</span>
                        <?= esc($pedido->entregador); ?>
                    </p>
                <?php endif; ?>
                <p class="card-text">
                    <span class="font-weight-bold">Observações:</span>
                    <?= esc($pedido->observacoes); ?>
                </p>

                <?php if ($pedido->deletado_em == null): ?>

                <?php else: ?>
                    <p class="card-text">
                        <span class="font-weight-bold text-danger">Excluído:</span>
                        <?= esc($pedido->deletado_em->humanize()); ?>
                    </p>
                <?php endif; ?>

                <?php $produtos = unserialize($pedido->produtos); ?>
                <ul class="list-group">
                    <?php foreach ($produtos as $produto): ?>
                        <li class="list-group-item">
                            <span class="font-weight-bold">Produto: </span> <?= esc($produto['nome']); ?>
                            <span class="font-weight-bold">Quantidade: </span> <?= esc($produto['quantidade']); ?>
                            <span class="font-weight-bold">Preço: </span> R$ <?= esc(number_format($produto['preco'], 2, ',', '.')); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div class="mt-4">
                    <div class="card-footer bg-primary d-flex justify-content-start pedido-actions">
                        <a href="<?= site_url("admin/pedidos"); ?>" class="btn btn-light btn-sm btn-icon-text mr-2">
                            <i class="mdi mdi-arrow-left btn-icon-prepend"></i> Voltar
                        </a>
                        <a href="<?= site_url("admin/pedidos/editar/$pedido->codigo"); ?>"
                            class="btn btn-warning btn-sm btn-icon-text mr-2">
                            <i class="mdi mdi-pencil btn-icon-prepend"></i> Alterar situação
                        </a>
                        <a href="<?= site_url("admin/pedidos/excluir/$pedido->codigo"); ?>"
                            class="btn btn-danger btn-sm btn-icon-text">
                            <i class="mdi mdi-delete btn-icon-prepend"></i> Excluir pedido
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?= $this->endSection() ?>
    <!-- Aqui enviamos para o template principal os scripts -->
    <?= $this->section('scripts'); ?>
    <script src="<?php echo site_url('admin/vendors/auto-complete/jquery-ui.js'); ?>"></script>
    <script>
        // Inicializa todos os tooltips da página
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    </script>

    <?= $this->endSection() ?>