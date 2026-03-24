<?= $this->extend('layout/principal_web'); ?>


<?= $this->section('titulo'); ?>
<?= $titulo; ?>
<?= $this->endSection() ?>

<?= $this->section('estilos'); ?>
<!-- Aqui enviamos para o template principal os estilos -->
<link href="<?php echo site_url('web/'); ?>src/assets/css/produto.css" type="text/css" rel="stylesheet" />
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

    @media (max-width: 767px) {
        .table-responsive h3 {
            font-size: 18px;
            margin: 8px 0;
        }

        .table-responsive .table {
            font-size: 11px;
            margin-bottom: 8px;
        }

        .table-responsive .table>thead>tr>th,
        .table-responsive .table>tbody>tr>td,
        .table-responsive .table>tbody>tr>th {
            padding: 4px;
            vertical-align: middle;
            line-height: 1.2;
        }

        .table-responsive .table>thead>tr>th:nth-child(3),
        .table-responsive .table>tbody>tr>td:nth-child(3) {
            display: none;
        }

        .table-responsive .form-inline .form-control {
            width: 52px;
            height: 28px;
            padding: 2px 4px;
            font-size: 11px;
        }

        .table-responsive .btn {
            padding: 4px 6px;
            font-size: 11px;
        }

        .table-responsive #valor-entrega,
        .table-responsive #total {
            white-space: nowrap;
        }

        .table-responsive .cep.form-control {
            height: 30px;
            font-size: 11px;
            padding: 4px 8px;
        }

        .carrinho-table .resumo-label {
            text-align: left !important;
            white-space: normal;
            word-break: break-word;
            font-size: 10px;
        }

        .carrinho-table .resumo-valor {
            text-align: left !important;
            white-space: nowrap;
            width: 1%;
        }

        .carrinho-table .resumo-linha td {
            padding-top: 3px;
            padding-bottom: 3px;
        }

        .carrinho-table .label-mobile {
            display: inline;
        }

        .carrinho-table .label-desktop {
            display: none;
        }

        .carrinho-table .resumo-valor {
            display: none;
        }

        .carrinho-table .produto-nome {
            white-space: pre-line;
            overflow-wrap: anywhere;
            word-break: break-word;
            line-height: 1.15;
        }
    }

    @media (min-width: 768px) {
        .carrinho-table .label-mobile {
            display: none;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('conteudo'); ?>
<div class="container" style="margin-top: 1em; margin-bottom: 2rem;">
    <!-- product -->
    <div class="product-content product-wrap clearfix product-deatil">
        <div class="row">
            <?php if (!isset($carrinho)): ?>
                <div class="text-center">
                    <h2 class="text-center" style="color: #bf2121">Seu carrinho está vazio.</h2>
                    <a href="<?php echo site_url('/'); ?>" class="btn btn-lg" style="background-color: #bf2121; border-color: #bf2121; color: white;">Mais delícias</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <h3 class="text-center">Seu Carrinho</h3>
                    <?php if (session()->has('errors_model')): ?>
                        <div class="alert alert-danger">
                            <?php foreach (session('errors_model') as $error): ?>
                                <p><?php echo esc($error); ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <table class="table table-striped carrinho-table">
                        <thead>
                            <tr>
                                <th class="text-center">Remover</th>
                                <th>Produto</th>
                                <th>Tamanho</th>
                                <th class="text-center">Quantidade</th>
                                <th>Preço</th>
                                <th>Sub totoal</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php $total = 0; ?>
                            <?php foreach ($carrinho as $produto): ?>
                                <tr>
                                    <th class="text-center" scope="row">
                                        <?php echo form_open('carrinho/remover', ['class' => 'form-inline']); ?>
                                        <div class="form-group">
                                            <input type="hidden" name="produto[slug]" value="<?php echo ($produto->slug); ?>">
                                        </div>
                                        <button type="submit" class="btn btn-danger float-right"><i class="fa fa-trash"></i></button>
                                        <?php echo form_close(); ?>
                                    </th>
                                    <td>
                                        <?php
                                        $nomeProduto = (string) $produto->nome;
                                        if (mb_stripos($nomeProduto, ' Com extra ') !== false) {
                                            $nomeProduto = preg_replace('/\s+Com extra\s+/iu', "\nCom extra ", $nomeProduto, 1);
                                        }
                                        ?>
                                        <span class="produto-nome"><?php echo esc($nomeProduto); ?></span>
                                    </td>
                                    <td><?php echo esc($produto->tamanho ?: '-'); ?></td>
                                    <td class="text-center">
                                        <?php echo form_open('carrinho/atualizar', ['class' => 'form-inline']); ?>
                                        <div class="form-group">
                                            <input type="number" class="form-control" name="produto[quantidade]" value="<?php echo ($produto->quantidade); ?>" min="1" max="10" step="1" required="">
                                            <input type="hidden" name="produto[slug]" value="<?php echo ($produto->slug); ?>">
                                        </div>
                                        <button type="submit" class="btn btn-primary float-right"><i class="fa fa-refresh"></i></button>
                                        <?php echo form_close(); ?>
                                    </td>
                                    <td>R$ <?php echo esc($produto->preco) ?></td>
                                    <?php $subTotal = $produto->preco * $produto->quantidade;
                                    $total += $subTotal;
                                    ?>
                                    <td>R$ <?php echo number_format($subTotal, 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php $taxaEntrega = 2.00; ?>
                            <?php $totalComEntrega = $total + $taxaEntrega; ?>
                            <tr class="resumo-linha">
                                <td class="text-right resumo-label" colspan="5" style="font-weight: bold">
                                    <strong class="label-desktop">Total produtos:</strong>
                                    <strong id="mobile-pedido" class="label-mobile">Pedido: R$ <?php echo number_format($total, 2, ',', '.'); ?></strong>
                                </td>
                                <td class="text-left resumo-valor">R$ <?php echo number_format($total, 2, ',', '.'); ?></td>
                            </tr>
                            <tr>
                                <td colspan="6">
                                    <label>Consulte a taxa de entrega:</label>
                                    <input type="text" name="bairro" class="form-control" placeholder="Digite o nome do bairro para calcular a taxa de entrega">
                                    <div id="cep"></div>
                                </td>
                            </tr>
                            <tr class="resumo-linha">
                                <td class="text-right border-top-0 resumo-label" colspan="5" style="font-weight: bold">
                                    <strong class="label-desktop">Taxa de entrega:</strong>
                                    <strong id="mobile-entrega" class="label-mobile">Entrega: R$ <?php echo number_format($taxaEntrega, 2, ',', '.'); ?></strong>
                                </td>
                                <td id="valor-entrega" class="text-left resumo-valor">R$ <?php echo number_format($taxaEntrega, 2, ',', '.'); ?></td>
                            </tr>
                            <tr class="resumo-linha">
                                <td class="text-right border-top-0 resumo-label" colspan="5" style="font-weight: bold">
                                    <strong class="label-desktop">Total pedido:</strong>
                                    <strong id="mobile-total" class="label-mobile">Total: R$ <?php echo number_format($totalComEntrega, 2, ',', '.'); ?></strong>
                                </td>
                                <td id="total" class="text-left resumo-valor">R$ <?php echo number_format($totalComEntrega, 2, ',', '.'); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Layout para celular -->
                <div class="d-md-none">
                    <div class="d-flex justify-content-center flex-wrap" style="gap: 8px;">
                        <a href="<?php echo site_url('carrinho/limpar'); ?>"
                            class="btn btn-sm"
                            style="min-width: 160px; background-color: #bf2121; border-color: #bf2121; color: white; font-family: 'Montserrat-Bold'; padding: 10px 12px; font-size: 13px; text-decoration: none;">
                            Limpar carrinho
                        </a>

                        <a href="<?php echo site_url('checkout'); ?>"
                            class="btn btn-sm"
                            style="min-width: 160px; background-color: #990100; border-color: #990100; color: #fff; font-family: 'Montserrat-Bold'; padding: 10px 12px; font-size: 13px; text-decoration: none;">
                            Finalizar pedido
                        </a>
                    </div>
                    <div style="margin-top: 8px; display: flex; justify-content: center;">
                        <a href="<?php echo site_url('/'); ?>"
                            class="btn btn-sm btn-info"
                            style="min-width: 160px; font-family: 'Montserrat-Bold'; padding: 10px 12px; font-size: 13px; text-decoration: none; color: white;">
                            Mais delícias
                        </a>
                    </div>
                </div>
        </div>
    <?php endif; ?>

    <!-- end product -->
    </div>
</div>
</div>
<?= $this->endSection() ?>

<!-- Aqui enviamos para o template principal os scripts -->
<?= $this->section('scripts'); ?>
<script src="<?php echo site_url('/admin/vendors/mask/jquery.mask.min.js'); ?>"></script>
<script src="<?php echo site_url('/admin/vendors/mask/app.js'); ?>"></script>
<script>
    $("[name=bairro]").focusout(function() {
        var bairro = $(this).val();

        if (!bairro || !bairro.trim()) {
            return;
        }

        $.ajax({
            type: 'GET',
            url: '<?php echo site_url('carrinho/consultacep'); ?>',
            dataType: 'json',
            data: {
                bairro: bairro
            },
            beforeSend: function() {
                $('#cep').html('<p>Consultando o bairro...</p>');
            },
            success: function(response) {
                if (!response.erro) {
                    $('#cep').html(response.bairro || '');

                    if (response.valor_entrega) {
                        var nomeBairro = response.nome_bairro ? String(response.nome_bairro).trim() : '';
                        var entregaDesktop = nomeBairro ? (nomeBairro + ' - ' + response.valor_entrega) : response.valor_entrega;
                        var entregaMobile = nomeBairro ? ('Entrega (' + nomeBairro + '): ' + response.valor_entrega) : ('Entrega: ' + response.valor_entrega);

                        $('#valor-entrega').text(entregaDesktop);
                        $('#mobile-entrega').text(entregaMobile);
                    }

                    if (response.total) {
                        $('#total').text(response.total);
                        $('#mobile-total').text('Total: ' + response.total);
                    }
                } else {
                    $('#cep').html(response.erro);
                }
            },
            error: function() {
                alert('Ocorreu um erro ao consultar o bairro. Por favor, entre em contato com a nossa equipe e informe o erro: CONSULTA-ERRO-TAXA-ENTREGA.');
            }
        });
    });
</script>

<?= $this->endSection(); ?>
<!-- Begin Sections-->