<?= $this->extend('layout/principal_web'); ?>

<?= $this->section('titulo'); ?>
<?= $titulo; ?>
<?= $this->endSection() ?>

<?= $this->section('estilos'); ?>
<style>
    .pedido-sucesso {
        padding: 3rem 0 2rem;
        min-height: 60vh;
        display: flex;
        align-items: center;
        background: linear-gradient(180deg, #fff7f5 0%, #ffffff 55%);
    }

    .pedido-sucesso-card {
        max-width: 760px;
        margin: 0 auto;
        background: #fff;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 14px 35px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(191, 33, 33, 0.08);
    }

    .pedido-sucesso-badge {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: rgba(25, 135, 84, 0.12);
        color: #198754;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin-bottom: 1rem;
    }

    .pedido-sucesso h1 {
        font-size: 2rem;
        margin-bottom: .75rem;
    }

    .pedido-sucesso .resumo {
        color: #555;
        font-size: 1rem;
        line-height: 1.7;
        margin-bottom: 1.25rem;
    }

    .pedido-sucesso .codigo {
        display: inline-block;
        background: #fff1ee;
        color: #bf2121;
        border-radius: 999px;
        padding: .45rem .85rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .pedido-sucesso .opcoes {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .pedido-sucesso .opcao {
        border: 1px solid #e8e8e8;
        border-radius: 16px;
        padding: 1rem;
        background: #fafafa;
        height: 100%;
    }

    .pedido-sucesso .opcao h3 {
        font-size: 1.05rem;
        margin-bottom: .5rem;
    }

    .pedido-sucesso .opcao p {
        color: #666;
        margin-bottom: .9rem;
    }

    .pedido-sucesso .btn {
        min-width: 100%;
        font-weight: 700;
        padding: .8rem 1rem;
    }

    .pedido-sucesso .btn-email {
        background: #bf2121;
        border-color: #bf2121;
        color: #fff;
    }

    .pedido-sucesso .btn-whatsapp {
        background: #25d366;
        border-color: #25d366;
        color: #fff;
    }

    .pedido-sucesso .mensagem-preview {
        margin-top: 1.5rem;
        padding: 1rem;
        border-radius: 14px;
        background: #f8f9fa;
        border: 1px dashed #dadada;
        color: #444;
        white-space: pre-line;
    }

    @media (max-width: 767px) {
        .pedido-sucesso {
            padding: 1.5rem 0;
        }

        .pedido-sucesso-card {
            padding: 1.25rem;
            border-radius: 16px;
        }

        .pedido-sucesso h1 {
            font-size: 1.5rem;
        }

        .pedido-sucesso .opcoes {
            grid-template-columns: 1fr;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('conteudo'); ?>
<?php $situacaoPedido = isset($pedido) ? (int) ($pedido->situacao ?? 0) : (int) ($situacao_pedido ?? 0); ?>
<div class="pedido-sucesso">
    <div class="container">
        <div class="pedido-sucesso-card">
            <div class="pedido-sucesso-badge">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <?php if ($situacaoPedido === 0): ?>
                <div class="codigo">Pedido <?= esc($codigo_pedido); ?></div>
                <h1><?= esc($titulo); ?></h1>
                <p class="resumo">
                    Seu pedido foi registrado com sucesso. Aguardando confirmação do estabelecimento.
                </p>

            <?php elseif ($situacaoPedido === 1): ?>
                <div class="codigo">Pedido <?= esc($codigo_pedido); ?></div>
                <h1>Saiu para entrega</h1>
                <p class="resumo">
                    Seu pedido saiu para entrega.
                </p>

            <?php elseif ($situacaoPedido === 2): ?>
                <div class="codigo">Pedido <?= esc($codigo_pedido); ?></div>
                <h1>Pedido entregue</h1>
                <p class="resumo">
                    Seu pedido foi entregue.
                </p>

            <?php elseif ($situacaoPedido === 3): ?>
                <div class="codigo">Pedido <?= esc($codigo_pedido); ?></div>
                <h1>Pedido cancelado</h1>
                <p class="resumo">
                    Seu pedido foi cancelado.
                </p>

            <?php else: ?>
                <div class="codigo">Pedido <?= esc($codigo_pedido); ?></div>
                <h1><?= esc($titulo); ?></h1>
                <p class="resumo">
                    Estamos atualizando o status do seu pedido.
                </p>
            <?php endif; ?>

            <?php if (in_array($situacaoPedido, [0, 1, 2, 3], true)): ?>
                <p class="resumo">
                    Escolha abaixo como prefere acompanhar as próximas mensagens.
                </p>
                <p class="resumo">
                    Canal selecionado: <strong><?= esc(ucfirst($canal_acompanhamento ?? 'email')); ?></strong>
                </p>

                <div class="opcoes">
                    <div class="opcao">
                        <h3>WhatsApp</h3>
                        <p>
                            Abra a conversa com a mensagem pronta usando o padrão do acompanhamento do pedido.
                        </p>
                        <form action="<?= site_url('checkout/atualizarcanal/' . $codigo_pedido); ?>" method="post">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="canal_acompanhamento" value="whatsapp">
                            <button type="submit" class="btn btn-whatsapp">Acompanhar por WhatsApp</button>
                        </form>
                    </div>

                    <div class="opcao">
                        <h3>E-mail</h3>
                        <p>
                            Receba as mensagens de acompanhamento no e-mail cadastrado e acompanhe o pedido pela sua conta.
                        </p>
                        <form action="<?= site_url('checkout/atualizarcanal/' . $codigo_pedido); ?>" method="post">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="canal_acompanhamento" value="email">
                            <button type="submit" class="btn btn-email">Acompanhar por E-mail</button>
                        </form>
                    </div>
                </div>

                <?php if (! empty($mensagem_acompanhamento)): ?>
                    <div class="mensagem-preview">
                        <?= esc($mensagem_acompanhamento); ?>
                    </div>
                <?php endif; ?>

                <h3 class="text-center">No momento o seu pedido está com o status de <?= $pedido->exibeSituacaoDoPedido(); ?></h3>

                <?php if ($situacaoPedido !== 3): ?>
                    <p class="resumo text-center">
                        Quando ocorrer a alteração de status do pedido, nós notificaremos você.
                    </p>
                <?php endif; ?>
            <?php endif; ?>

            <div class="col-md-12">
                <?php $produtosPedido = isset($pedido) && $pedido ? $pedido->getProdutosPedido() : []; ?>
                <ul class="list-group">
                    <?php foreach ($produtosPedido as $produto): ?>
                        <li class="list-group-item">
                            <div>
                                <h4><?= esc(ellipsize($produto['nome'], 60)); ?></h4>
                                <p class="text-muted">Quantidade: <?= esc($produto['quantidade']); ?></p>
                                <p class="text-muted">Preço: <?= esc(number_format($produto['preco'], 2, ',', '.')); ?></p>
                            </div>
                        </li>
                    <?php endforeach; ?>

                    <li class="list-group-item">
                        <span>Data do pedido:</span>
                        <strong><?= $pedido->criado_em->humanize(); ?></strong>
                    </li>
                    <li class="list-group-item">
                        <span>Total de produtos:</span>
                        <strong><?= esc(number_format($pedido->valor_produtos, 2, ',', '.')); ?></strong>
                    </li>
                    <li class="list-group-item">
                        <span>Taxa de entrega:</span>
                        <strong><?= esc(number_format($pedido->valor_entrega, 2, ',', '.')); ?></strong>
                    </li>
                    <li class="list-group-item">
                        <span>Valor final:</span>
                        <strong><?= esc(number_format($pedido->valor_pedido, 2, ',', '.')); ?></strong>
                    </li>
                    <li class="list-group-item">
                        <span>Endereço de entrega:</span>
                        <strong><?= esc(ucfirst($pedido->endereco_entrega ?? 'Endereço não informado')); ?></strong>
                    </li>
                    <li class="list-group-item">
                        <span>Forma de pagamento:</span>
                        <strong><?= esc(ucfirst($pedido->forma_pagamento ?? 'à vista')); ?></strong>
                    </li>
                    <li class="list-group-item">
                        <span>Observações:</span>
                        <strong><?= esc(ucfirst($pedido->observacoes ?? 'Nenhuma observação')); ?></strong>
                    </li>
                </ul>
                <a href="<?php echo site_url("/") ?>" class="btn btn-primary">Voltar à página inicial</a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>