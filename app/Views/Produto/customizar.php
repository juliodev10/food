<?= $this->extend('layout/principal_web'); ?>


<?= $this->section('titulo'); ?>
<?= $titulo; ?>
<?= $this->endSection() ?>

<?= $this->section('estilos'); ?>
<!-- Aqui enviamos para o template principal os estilos -->
<link href="<?php echo site_url('web/'); ?>src/assets/css/produto.css" type="text/css" rel="stylesheet" />

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
                            <?= esc($opcao->nome); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
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
                <?= view('footer') ?>
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
        $('#btn-adiciona').prop('value', 'Selecione um tamanho');
        $('#segunda_metade').html('<option value="">Escolha a segunda metade...</option>');

        $('#primeira_metade').on('change', function() {

            var primeira_metade = $(this).val();

            if (primeira_metade) {
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
                    beforeSend: function(data) {},

                    success: function(data) {
                        if (data && data.imagemPrimeiroProduto) {
                            $('#img-primeira-metade').attr('src', data.imagemPrimeiroProduto + '?v=' + Date.now());
                        }
                    },
                });
            } else {
                $('#img-primeira-metade').attr('src', imagemPlaceholder);
                $('#img-segunda-metade').attr('src', imagemPlaceholder);
                $('#segunda_metade').html('<option value="">Escolha a segunda metade...</option>');

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
                $('#img-segunda-metade').attr('src', imagemPlaceholder);
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
                    beforeSend: function(data) {},

                    success: function(data) {
                        if (data && data.imagemPrimeiroProduto) {
                            $('#img-primeira-metade').attr('src', data.imagemPrimeiroProduto + '?v=' + Date.now());
                        }

                        if (data && data.imagemSegundoProduto) {
                            $('#img-segunda-metade').attr('src', data.imagemSegundoProduto + '?v=' + Date.now());
                        }
                    },
                });
            }
        });
    });
</script>


<?= $this->endSection() ?>