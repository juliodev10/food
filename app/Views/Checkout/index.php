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

    .checkout-acoes {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 10px;
    }

    .checkout-acoes .btn {
        min-width: 160px;
        font-family: 'Montserrat-Bold';
        padding: 10px 12px;
        font-size: 13px;
        text-decoration: none;
    }

    @media (max-width: 767px) {
        .table-responsive h3 {
            font-size: 18px;
            margin: 8px 0;
        }

        .checkout-acoes {
            flex-wrap: nowrap;
            gap: 6px;
        }

        .checkout-acoes .btn {
            min-width: 0;
            flex: 1 1 0;
            padding: 8px 9px;
            font-size: 12px;
            line-height: 1.2;
            text-align: center;
            white-space: normal;
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

        .table-responsive .bairro.form-control {
            height: 30px;
            font-size: 11px;
            padding: 4px 8px;
        }

        #rua,
        #numero {
            pointer-events: auto !important;
            background-color: #fff !important;
            -webkit-text-fill-color: #333 !important;
            position: relative;
            z-index: 5;
            -webkit-user-select: text;
            user-select: text;
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
            <div class="container" style="margin-top: 2em;">
                <h2 class="section-title pull-left"><?php echo esc($titulo) ?></h2>
            </div>

            <div class="col-md-7">
                <ul class="list-group">
                    <?php $total = 0; ?>
                    <?php foreach ($carrinho as $produto) : ?>
                        <?php $subTotal = $produto['preco'] * $produto['quantidade']; ?>
                        <?php $total += $subTotal; ?>
                        <li class="list-group-item">
                            <div>
                                <h4><?php echo ellipsize($produto['nome'], 60); ?></h4>
                                <p class="text-muted">
                                    Preço: R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?> | Quantidade: <?php echo $produto['quantidade']; ?> | Subtotal: R$ <?php echo number_format($subTotal, 2, ',', '.'); ?>
                                </p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                    <li class="list-group-item">
                        <span>Total de produtos</span>
                        <strong>R$ <?php echo number_format($total, 2, ',', '.'); ?></strong>
                    </li>
                    <li class="list-group-item">
                        <span>Taxa de entrega</span>
                        <strong id="valor_entrega" class="text-danger">Obrigatório*</strong>
                    </li>
                    <li class="list-group-item">
                        <span>Valor do pedido</span>
                        <strong id="total"> R$ <?php echo number_format($total, 2, ',', '.'); ?></strong>
                    </li>
                    <li class="list-group-item">
                        <span>Endereço de entrega</span>
                        <strong id="endereco" class="text-danger">Obrigatório*</strong>
                    </li>
                </ul>

                <div class="checkout-acoes">
                    <a href="<?php echo site_url('carrinho'); ?>"
                        class="btn btn-sm"
                        style="background-color: #bf2121; border-color: #bf2121; color: white;">
                        Meu carrinho
                    </a>
                    <a href="<?php echo site_url('/'); ?>"
                        class="btn btn-sm btn-info"
                        style="color: white;">
                        Continuar comprando
                    </a>
                </div>
            </div><!-- .col-md-7 -->
            <hr>
            <div class="col-md-5">
                <?php echo form_open('checkout/processar', ['id' => 'form-checkout']); ?>

                <?php if (session()->has('errors_model')): ?>
                    <div class="alert alert-danger">
                        <?php foreach (session('errors_model') as $error): ?>
                            <p><?php echo esc($error); ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <p class="mt-6 mt-md-2 font-weight-bold" style="font-size: 18px;">
                    Escolha a forma de pagamento:
                </p>
                <?php foreach ($formas as $forma): ?>
                    <div class="radio">
                        <label>
                            <input id="forma" type="radio" name="forma" style="margin-top: 2px;" class="forma" data-forma="<?php echo $forma->id; ?>">
                            <span style="font-size: 15px;"><?php echo esc($forma->nome); ?></span>
                        </label>
                    </div>
                <?php endforeach; ?>
                <hr>
                <div id="troco" class="hidden">
                    <div class="form-group col-md-12" style="padding-left: 0;">
                        <label>Troco para</label>
                        <input type="text" class="form-control" id="troco_para" name="checkout[troco_para]" placeholder="Troco para">
                        <label>
                            <input type="checkbox" id="sem_troco" name="checkout[sem_troco]">
                            Não quero troco
                        </label>
                    </div>
                </div><!-- Fim troco -->
                <div class="form-group col-md-12" style="padding-left: 0;">
                    <label>Consulte a taxa de entrega</label>
                    <input type="text" name="bairro_slug" class="form-control bairro" placeholder="Informe seu Bairro" value="">
                    <div id="bairro-consulta" class="mt-2"></div>
                </div>
                <div class="form-group col-xs-9 col-md-9" style="padding-left: 0;">
                    <label>Rua*</label>
                    <input id="rua" type="text" name="checkout[rua]" class="form-control" autocomplete="address-line1" inputmode="text" onfocus="this.readOnly=false;this.disabled=false;" ontouchstart="this.readOnly=false;this.disabled=false;" required>
                </div>
                <div class="form-group col-xs-3 col-md-3">
                    <label>Numero*</label>
                    <input id="numero" type="tel" name="checkout[numero]" class="form-control" autocomplete="address-line2" inputmode="numeric" onfocus="this.readOnly=false;this.disabled=false;" ontouchstart="this.readOnly=false;this.disabled=false;" required>
                </div>
                <div class="form-group col-md-12" style="padding-left: 0;">
                    <label>Ponto de referência</label>
                    <input type="text" name="checkout[referencia]" class="form-control" placeholder="Informe um ponto de referencia" required>
                </div>
                <div class="form-group col-md-12" style="padding-left: 0;">
                    <input type="text" id="forma_id" name="checkout[forma_id]" placeholder="checkout[forma_id]">
                    <input type="text" id="bairro_slug" name="checkout[bairro_slug]" placeholder="checkout[bairro_slug]">
                </div>
                <div class="form-group col-md-12" style="padding-left: 0;">
                    <input type="submit" id="btn-checkout" class="btn btn-block" style="background-color: #bf2121; border-color: #bf2121; color: white;" value="Antes, consulte a taxa de entrega">
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<!-- Aqui enviamos para o template principal os scripts -->
<?= $this->section('scripts'); ?>
<script src=" <?php echo site_url('/admin/vendors/mask/jquery.mask.min.js'); ?>"></script>
<script src="<?php echo site_url('/admin/vendors/mask/app.js'); ?>"></script>
<script>
    $("#btn-checkout").prop('disabled', true);

    function liberarCamposEndereco() {
        $('#rua, #numero')
            .prop('readonly', false)
            .prop('disabled', false)
            .removeAttr('readonly')
            .removeAttr('disabled');
    }

    liberarCamposEndereco();

    $('#rua, #numero').on('focus touchstart click', function() {
        liberarCamposEndereco();
    });

    $(document).on('focusin touchstart mousedown', '#rua, #numero', function() {
        this.readOnly = false;
        this.disabled = false;
        $(this).removeAttr('readonly').removeAttr('disabled');

        var input = this;
        setTimeout(function() {
            input.focus();
        }, 0);
    });

    var taxaConsultada = false;

    function atualizarEstadoBotaoCheckout() {
        var bairro = $.trim($('[name="bairro_slug"]').val() || '');
        var rua = $.trim($('#rua').val() || '');
        var numero = $.trim($('#numero').val() || '');
        var formaId = $.trim($('#forma_id').val() || '');
        var podeProcessar = taxaConsultada && bairro !== '' && rua !== '' && numero !== '' && formaId !== '';

        $("#btn-checkout").prop('disabled', !podeProcessar);
        $("#btn-checkout").val(podeProcessar ? 'Processar pedido' : 'Antes, consulte a taxa de entrega');
    }

    $(".forma").on('click', function() {
        var forma_id = $(this).attr('data-forma');
        $("#forma_id").val(forma_id);
        atualizarEstadoBotaoCheckout();
        if (forma_id == 1) {
            $("#troco").removeClass('hidden');
        } else {
            $("#troco").addClass('hidden');
        }
    });
    $("#sem_troco").on('click', function() {
        if (this.checked) {
            $("#troco_para").prop('disabled', true);
            $("#troco_para").val('Sem troco');
            $("#troco_para").attr('placeholder', 'Sem troco');
        } else {
            $("#troco_para").prop('disabled', false);
            $("#troco_para").attr('placeholder', 'Troco para');
            $("#troco_para").val('');
        }
    }); //fim #sem_troco

    function atualizarResumoEndereco() {
        var rua = $.trim($('#rua').val() || '');
        var numero = $.trim($('[name="checkout[numero]"]').val() || '');
        var referencia = $.trim($('[name="checkout[referencia]"]').val() || '');

        var partes = [];
        if (rua) {
            partes.push(rua);
        }
        if (numero) {
            partes.push('Nº ' + numero);
        }
        if (referencia) {
            partes.push('Ref: ' + referencia);
        }

        if (partes.length > 0) {
            $('#endereco').removeClass('text-danger').addClass('text-success').text(partes.join(' - '));
        }
    }

    $('#rua, [name="checkout[numero]"], [name="checkout[referencia]"]').on('input blur', function() {
        atualizarResumoEndereco();
        atualizarEstadoBotaoCheckout();
    });

    $('[name="bairro_slug"]').on('input blur', function() {
        var valorDigitado = $.trim($(this).val() || '');

        // Copiar o valor digitado para o hidden field
        $('#bairro_slug').val(valorDigitado);

        if (!valorDigitado) {
            taxaConsultada = false;
            $('#valor_entrega').removeClass('text-success').addClass('text-danger').text('Obrigatório*');
        }
        atualizarEstadoBotaoCheckout();
    });


    $('[name=bairro_slug]').focusout(function() {
        var bairro_slug = $(this).val();

        if (!bairro_slug || !bairro_slug.trim()) {
            return;
        }

        $.ajax({
            type: 'GET',
            url: '<?php echo site_url('checkout/consultabairro'); ?>',
            dataType: 'json',
            data: {
                bairro_slug: bairro_slug
            },
            beforeSend: function() {
                taxaConsultada = false;
                $('#bairro-consulta').html('<p>Consultando o bairro...</p>');
                $("#btn-checkout").prop('disabled', true);
                $("#btn-checkout").val('Consultando a taxa de entrega...');
            },
            success: function(response) {
                if (!response.erro) {
                    $('#bairro_slug').val(response.bairro_slug);

                    if (response.valor_entrega) {
                        var nomeBairro = response.nome_bairro ? String(response.nome_bairro).trim() : '';
                        var entregaDesktop = nomeBairro ? (nomeBairro + ' - ' + response.valor_entrega) : response.valor_entrega;

                        if (nomeBairro) {
                            $('[name="bairro_slug"]').val(nomeBairro);
                        } else {
                            $('[name="bairro_slug"]').val(response.bairro_slug);
                        }

                        $('#valor_entrega').removeClass('text-danger').addClass('text-success').text(entregaDesktop);

                        $("#btn-checkout").prop('disabled', true);
                        $("#btn-checkout").val('Consultando a taxa de entrega...');
                        atualizarResumoEndereco();
                        $('#bairro-consulta').html(response.bairro);

                    }

                    if (response.total) {
                        $('#total').text(response.total);
                        $('#mobile-total').text('Total: ' + response.total);
                        taxaConsultada = true;
                        atualizarEstadoBotaoCheckout();
                    }
                } else {
                    taxaConsultada = false;
                    $('#bairro-consulta').html(response.erro);
                    atualizarEstadoBotaoCheckout();
                }
            },
            error: function() {
                taxaConsultada = false;
                alert('Ocorreu um erro ao consultar o bairro. Por favor, entre em contato com a nossa equipe e informe o erro: CONSULTA-ERRO-TAXA-ENTREGA-CHECKOUT');
                $("#btn-checkout").prop('disabled', true);
                $("#btn-checkout").val('Antes, consulte a taxa de entrega');
                atualizarEstadoBotaoCheckout();
            }
        });
    });
</script>

<?= $this->endSection(); ?>
<!-- Begin Sections-->