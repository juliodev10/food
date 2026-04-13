<?= $this->extend('layout/principal_web'); ?>


<?= $this->section('titulo'); ?>
<?= $titulo; ?>
<?= $this->endSection() ?>

<?= $this->section('estilos'); ?>
<!-- Aqui enviamos para o template principal os estilos -->
<link href="<?php echo site_url('web/'); ?>src/assets/css/produto.css" type="text/css" rel="stylesheet" />
<style>
    @media (max-width: 767px) {
        .produto-footer-fullwidth {
            width: 100vw;
            max-width: 100vw;
            margin-left: calc(50% - 50vw);
            margin-right: calc(50% - 50vw);
            padding: 0;
            overflow-x: hidden;
        }

        .produto-footer-fullwidth .site-footer {
            width: 100%;
            margin: 0;
            padding-left: 16px;
            padding-right: 16px;
            box-sizing: border-box;
        }

        .produto-footer-fullwidth .footer-content {
            width: 100%;
            margin-left: 0;
            margin-right: 0;
        }
    }
</style>

<?= $this->endSection() ?>

<?= $this->section('conteudo'); ?>
<?php $imagemProduto = site_url("produto/imagem/{$produto->id}"); ?>
<div class="container">
    <!-- product -->
    <div class="product-content product-wrap clearfix product-deatil">
        <div class="col-md-12">

            <h2 class="name">
                <?= esc($titulo); ?>
            </h2>

            <?php echo form_open('carrinho/especial'); ?>

            <div class="row">
                <div class="col-md-12">
                    <?php if (session()->has('errors_model')): ?>
                        <div class="col-xs-12">
                            <div class="alert alert-danger alert-dismissible fade in" role="alert">
                                <strong>Erro na validação!</strong>
                                <ul>
                                    <?php foreach (session('errors_model') as $error): ?>
                                        <li>
                                            <?= ($error) ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php $temCustomizacao = false; ?>
                    <?php foreach ($especificacoes as $especificacao): ?>
                        <?php if ($especificacao->customizavel): ?>
                            <?php $temCustomizacao = true; ?>
                            <?php break; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>

                </div>
            </div>

            <div class="col-md-6">
                <div id="ImagemPrimeiroProduto" style="margin-bottom: 1em; margin-top: 1em;">
                    <img id="img-primeira-metade" class="img-responsive center-block d-block mx-auto"
                        src="<?php echo site_url("/web/src/assets/img/escolha_produto.png"); ?>" width="200px"
                        alt="Escolha um produto">
                </div>
                <label>Escolha a primeira metade do produto</label>
                <select id="primeira_metade" class="form-control" name="primeira_metade">
                    <option value="">Escolha seu produto...</option>
                    <?php foreach ($opcoes as $opcao): ?>
                        <option value="<?= $opcao->id; ?>">
                            <?= esc($opcao->nome); ?> - R$ <?= esc(number_format((float) $opcao->preco_base, 2, ',', '.')); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <div class="form-group" style="margin-top: 10px;">
                    <input type="hidden" id="extra_id" name="extra_id" class="form-control" placeholder="extra_id_hidden">
                </div>
            </div>

            <div class="col-md-6">
                <div id="ImagemSegundoProduto" style="margin-bottom: 1em; margin-top: 1em;">
                    <img id="img-segunda-metade" class="img-responsive center-block d-block mx-auto"
                        src="<?php echo site_url("/web/src/assets/img/escolha_produto.png"); ?>" width=" 200px " alt="
                    Escolha um produto" alt="">
                </div>
                <label>Escolha a segunda metade</label>
                <select id="segunda_metade" class="form-control" name="segunda_metade">
                    <option value="">Escolha a segunda metade...</option>
                </select>
            </div>

            <div class="col-md-12">
                <div id="valor_produto_customizado" style="font-size: 18px; color: #990100; font-family: 'Montserrat-Bold';">
                    <div id="valor_metades_customizadas"></div>
                    <div id="valor_total_metades"></div>
                    <div id="valor_extra_customizado"></div>
                </div>
            </div>

            <div class="col-md-6">
                <div id="boxInfoExtras" style="display: none;">
                    <p class="small">Extras</p>
                    <div class="radio">
                        <label>
                            <input type="radio" class="extra" name="extra" checked="">Sem extra
                        </label>
                    </div>
                    <div id="extras">

                    </div>
                </div>
            </div>

            <div class="col-md-12" style="margin-top: 10px;">
                <div class="form-group">
                    <label for="observacao">Observação do pedido</label>
                    <textarea
                        id="observacao"
                        name="observacao"
                        class="form-control"
                        rows="3"
                        maxlength="200"
                        placeholder="Ex.: sem cebola, retirar tomate, molho separado..."><?php echo esc(old('observacao')); ?></textarea>
                    <small class="text-muted">Essa observação será salva junto ao pedido.</small>
                </div>
            </div>

            <div class="col-md-12" style="margin-top: 15px;">
                <div class="row" style="display: flex; flex-wrap: wrap; gap: 2px; margin: 0;">
                    <div style="padding: 0;">
                        <input type="submit" id="btn-adiciona" class="btn btn-success" value="Selecione um valor">
                    </div>

                    <div style="padding: 0;">
                        <a href="<?php echo site_url('produto/detalhes/ ' . $produto->slug); ?>"
                            class="btn btn-info">Voltar</a>
                    </div>
                </div>
            </div>

            <div class="col-md-12" style="margin-top: 20px;">
                <div class="produto-footer-fullwidth">
                    <?= view('footer') ?>
                </div>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>
    <!-- end product -->
</div>

<?= $this->endSection() ?>

<!-- Aqui enviamos para o template principal os scripts -->

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        var imagemPlaceholder = '<?= site_url('/web/src/assets/img/escolha_produto.png'); ?>';
        var baseImagemProduto = '<?= site_url('produto/imagem'); ?>';
        var categoria_id = '<?= $produto->categoria_id; ?>';
        var totalMetadesAtual = 0;
        var valorExtraAtual = 0;

        function atualizarTotalComExtra() {
            var totalComExtra = (totalMetadesAtual + valorExtraAtual).toFixed(2);
            var labelTotal = valorExtraAtual > 0 ? 'Total com extra' : 'Total das metades';
            $('#valor_total_metades').html('<p class="small"><strong>' + labelTotal + '</strong> - R$: ' + totalComExtra + '</p>');
        }

        function resetExtras() {
            $('#extras').html('');
            $('#boxInfoExtras').hide();
            $('input.extra[name="extra"]').prop('checked', false);
            $('input.extra[name="extra"]').first().prop('checked', true);
            $('#extra_id').val('');
            $('#valor_extra_customizado').html('');
            valorExtraAtual = 0;
            if (totalMetadesAtual > 0) {
                atualizarTotalComExtra();
            }
        }

        function renderExtras(extras) {
            if (!Array.isArray(extras) || extras.length === 0) {
                resetExtras();
                return;
            }

            let extrasHtml = '';
            extras.forEach(function(extra) {
                extrasHtml += '<div class="radio">';
                extrasHtml += '<label>';
                extrasHtml += '<input type="radio" class="extra" name="extra" value="' + extra.preco + '" data-extra="' + extra.id + '">';
                extrasHtml += extra.nome + ' - R$ ' + parseFloat(extra.preco).toFixed(2);
                extrasHtml += '</label>';
                extrasHtml += '</div>';
            });

            $('#extras').html(extrasHtml);
            $('#boxInfoExtras').show();
            $('input.extra[name="extra"]').first().prop('checked', true);
            $('#extra_id').val('');
            $('#valor_extra_customizado').html('');
        }

        function preencherSegundaMetade(primeiraMetadeId) {
            $('#segunda_metade').html('<option value="">Escolha a segunda metade...</option>');

            $('#primeira_metade option').each(function() {
                var id = $(this).val();
                var nome = $(this).text().trim();

                if (id && id !== primeiraMetadeId) {
                    $('#segunda_metade').append($('<option />').attr('value', id).text(nome));
                }
            });

            if (primeiraMetadeId && $('#segunda_metade option').length === 1) {
                $('#segunda_metade').html('<option value="">Não encontramos opções de customização</option>');
            }
        }

        $('#btn-adiciona').prop('disabled', true);
        $('#btn-adiciona').prop('value', 'Selecione os produtos');
        $('#segunda_metade').html('<option value="">Escolha a segunda metade...</option>');

        $('#primeira_metade').on('change', function() {

            var primeira_metade = $(this).val();

            if (primeira_metade) {
                $('#btn-adiciona').prop('disabled', true);
                $('#btn-adiciona').prop('value', 'Selecione os produtos');
                resetExtras();
                preencherSegundaMetade(primeira_metade);

                var primeiraImagem = baseImagemProduto + '/' + primeira_metade + '?v=' + Date.now();
                $('#img-primeira-metade').attr('src', primeiraImagem);
                $('#img-segunda-metade').attr('src', imagemPlaceholder);

                $.ajax({
                    type: 'GET',
                    url: '<?php echo site_url('produto/procurar'); ?>',
                    dataType: 'json',
                    data: {
                        primeira_metade: primeira_metade,
                        categoria_id: categoria_id
                    },
                    success: function(data) {
                        if (data && data.imagemPrimeiroProduto) {
                            $('#img-primeira-metade').attr('src', data.imagemPrimeiroProduto + '?v=' + Date.now());
                        }
                    },
                });
            } else {
                $('#btn-adiciona').prop('disabled', true);
                $('#btn-adiciona').prop('value', 'Selecione os produtos');
                $('#img-primeira-metade').attr('src', imagemPlaceholder);
                $('#img-segunda-metade').attr('src', imagemPlaceholder);
                $('#segunda_metade').html('<option value="">Escolha a segunda metade...</option>');
                totalMetadesAtual = 0;
                $('#valor_metades_customizadas').html('');
                $('#valor_total_metades').html('');
                $('#valor_extra_customizado').html('');
                resetExtras();
            }
        });

        $("#segunda_metade").on('change', function() {
            var primeiro_produto_id = $('#primeira_metade').val();
            var segundo_produto_id = $(this).val();

            if (primeiro_produto_id) {
                var primeiraImagem = baseImagemProduto + '/' + primeiro_produto_id + '?v=' + Date.now();
                $('#img-primeira-metade').attr('src', primeiraImagem);
            }

            if (!segundo_produto_id) {
                $('#btn-adiciona').prop('disabled', true);
                $('#btn-adiciona').prop('value', 'Selecione os produtos');
                $('#img-segunda-metade').attr('src', imagemPlaceholder);
                totalMetadesAtual = 0;
                $('#valor_metades_customizadas').html('');
                $('#valor_total_metades').html('');
                resetExtras();
                return;
            }

            var segundaImagem = baseImagemProduto + '/' + segundo_produto_id + '?v=' + Date.now();
            $('#img-segunda-metade').attr('src', segundaImagem);

            if (primeiro_produto_id && segundo_produto_id) {
                $.ajax({
                    type: 'GET',
                    url: '<?php echo site_url('produto/exibetamanhos'); ?>',
                    dataType: 'json',
                    data: {
                        primeiro_produto_id: primeiro_produto_id,
                        segundo_produto_id: segundo_produto_id,
                        categoria_id: categoria_id
                    },

                    success: function(data) {
                        if (data && data.imagemPrimeiroProduto) {
                            $('#img-primeira-metade').attr('src', data.imagemPrimeiroProduto + '?v=' + Date.now());
                        }

                        if (data && data.imagemSegundoProduto) {
                            $('#img-segunda-metade').attr('src', data.imagemSegundoProduto + '?v=' + Date.now());
                        }

                        if (data && data.primeira_metade && data.segunda_metade) {
                            var precoPrimeiraNumero = parseFloat(data.primeira_metade.preco);
                            var precoSegundaNumero = parseFloat(data.segunda_metade.preco);
                            var precoPrimeiraMetade = (precoPrimeiraNumero / 2);
                            var precoSegundaMetade = (precoSegundaNumero / 2);
                            var precoPrimeira = precoPrimeiraMetade.toFixed(2);
                            var precoSegunda = precoSegundaMetade.toFixed(2);
                            totalMetadesAtual = (precoPrimeiraMetade + precoSegundaMetade);
                            var htmlValores = '';
                            htmlValores += '<p class="small"><strong>' + data.primeira_metade.nome + ' (1/2)</strong> - R$: ' + precoPrimeira + '</p>';
                            htmlValores += '<p class="small"><strong>' + data.segunda_metade.nome + ' (1/2)</strong> - R$: ' + precoSegunda + '</p>';
                            $('#valor_metades_customizadas').html(htmlValores);
                            atualizarTotalComExtra();

                            $('#btn-adiciona').prop('disabled', false);
                            $('#btn-adiciona').prop('value', 'Adicionar');
                        }

                        if (data && data.extras) {
                            let extrasHtml = '';
                            data.extras.forEach(function(extra) {
                                extrasHtml += '<div class="radio">';
                                extrasHtml += '<label>';
                                extrasHtml += '<input type="radio" class="extra" name="extra" value="' + extra.preco + '" data-extra="' + extra.id + '">';
                                extrasHtml += extra.nome + ' - R$ ' + parseFloat(extra.preco).toFixed(2);
                                extrasHtml += '</label>';
                                extrasHtml += '</div>';
                            });
                            $('#extras').html(extrasHtml);
                            $('#boxInfoExtras').show();
                        } else {
                            $('#extras').html('');
                            $('#boxInfoExtras').hide();
                        }
                    },
                });
            }
        });

        $(document).on('change', '.extra', function() {
            var extra_id = $(this).attr('data-extra');

            if (!extra_id) {
                $("#extra_id").val('');
                $("#valor_extra_customizado").html('');
                valorExtraAtual = 0;
                if (totalMetadesAtual > 0) {
                    atualizarTotalComExtra();
                }
                return;
            }

            $.ajax({
                type: 'GET',
                url: '<?php echo site_url('produto/exibeValor'); ?>',
                dataType: 'json',
                data: {
                    extra_id: extra_id
                },
                success: function(data) {
                    if (!data || !data.extra) {
                        $("#extra_id").val('');
                        $("#valor_extra_customizado").html('');
                        return;
                    }

                    $("#extra_id").val(data.extra.id);
                    valorExtraAtual = parseFloat(data.extra.preco);
                    $("#valor_extra_customizado").html('<p class="small">Extra selecionado: ' + data.extra.nome + ' - R$: ' + parseFloat(data.extra.preco).toFixed(2) + '</p>');
                    if (totalMetadesAtual > 0) {
                        atualizarTotalComExtra();
                    }
                }
            });
        });
    });
</script>


<?= $this->endSection() ?>