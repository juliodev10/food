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
</style>
<?= $this->endSection() ?>

<?= $this->section('conteudo'); ?>
<?php $imagemProduto = site_url("produto/imagem/{$produto->id}"); ?>
<div class="container">
    <!-- product -->
    <div class="product-content product-wrap clearfix product-deatil">
        <div class="row">

            <div class="col-md-4 col-sm-12 col-xs-12">
                <div class="product-image">
                    <img src="<?= $imagemProduto; ?>" alt="<?= esc($produto->nome); ?>" class="img-responsive" />
                </div>
            </div>

            <?php if (session()->has('errors_model')): ?>
                <div class="col-xs-12">
                    <div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <strong>Erro na validação!</strong>
                        <ul>
                            <?php foreach (session('errors_model') as $error): ?>
                                <li><?= ($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            <?php endif; ?>

            <?php echo form_open('carrinho/adicionar'); ?>
            <div class="col-md-7 col-md-offset-1 col-sm-12 col-xs-12">
                <h2 class="name">
                    <?= esc($produto->nome); ?>
                </h2>
                <hr />
                <h3 class="price-container">
                    <p class="small">Escolha o valor</p>

                    <?php foreach ($especificacoes as $especificacao): ?>
                        <div class="radio">
                            <label>
                                <input type="radio" class="especificacao"
                                    data-especificacao="<?php echo $especificacao->especificacao_id; ?>"
                                    name="produto[preco]" value="<?php echo $especificacao->preco; ?>">
                                <?php echo esc($especificacao->nome); ?>
                                <?php echo esc(number_format($especificacao->preco, 2)); ?>
                            </label>
                        </div>
                    <?php endforeach; ?>

                    <?php if (isset($extras) && !empty($extras)): ?>
                        <hr>
                        <p class="small">Extras do produto</p>
                        <div class="radio">
                            <label>
                                <input type="radio" class="extra" name="extra" checked="">Sem extra
                            </label>
                        </div>
                        <?php foreach ($extras as $extra): ?>
                            <div class="radio">
                                <label>
                                    <input type="radio" class="extra" data-extra="<?php echo $extra->id_principal; ?>"
                                        name="extra" value="<?php echo $extra->preco; ?>">
                                    <?php echo esc($extra->nome); ?>
                                    <?php echo esc(number_format($extra->preco, 2)); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </h3>

                <div class="row" style="margin-top: 4rem">
                    <div class="col-md-4">
                        <label>Quantidade</label>
                        <input type="number" class="form-control" name="produto[quantidade]" placeholder="Quantidade"
                            value="1" min="1" max="10" step="1" required="">
                    </div>
                </div>

                <hr />
                <div class=" description description-tabs">

                    <div id="myTabContent" class="tab-content">
                        <div class="tab-pane fade active in" id="more-information">
                            <br />
                            <strong>É uma delícia</strong>
                            <p>
                                <?= esc($produto->ingredientes ?: 'Ingredientes não informados.'); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <hr />
                <div>
                    <!--Campos hidden que usaremos no controller do carrinho-->
                    <input type="text" name="produto[slug]" placeholder="produto[slug]"
                        value="<?= esc($produto->slug); ?>">
                    <input type="text" id="especificacao_id" name="produto[especificacao_id]"
                        placeholder="Especificação ID">
                    <input type="text" id="extra_id" name="produto[extra_id]" placeholder="produto[extra_id]">
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <input id="btn-adiciona" type="submit" class="btn btn-success btn-block" value="Adicionar">
                    </div>

                    <?php foreach ($especificacoes as $especificacao): ?>
                        <?php if ($especificacao->customizavel): ?>
                            <div class="col-sm-4">
                                <a href="<?php echo site_url('produto/customizar/' . $produto->slug); ?>"
                                    class="btn btn-primary btn-block">Customizar</a>
                            </div>
                            <?php break; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <div class="col-sm-4">
                        <a href="<?php echo site_url('/'); ?>" class="btn btn-info btn-block">Mais delícias</a>
                    </div>
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
    $(document).ready(function () {
        var especificacao_id;
        if (!especificacao_id) {
            $('#btn-adiciona').prop('disabled', true);
            $('#btn-adiciona').prop('value', 'Selecione um valor');
        }
        $(".especificacao").on('click', function () {
            especificacao_id = $(this).attr('data-especificacao');
            $("#especificacao_id").val(especificacao_id);

            $('#btn-adiciona').prop('disabled', false);
            $('#btn-adiciona').prop('value', 'Adicionar');
        });

        $(".extra").on('click', function () {
            var extra_id = $(this).attr('data-extra');
            $("#extra_id").val(extra_id);
        });
    });
</script>

<?= $this->endSection() ?>
<!-- Begin Sections-->