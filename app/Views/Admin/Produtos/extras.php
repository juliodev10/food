<?= $this->extend('Admin/layout/principal'); ?>


<?= $this->section('titulo'); ?>
<?= $titulo; ?>
<?= $this->endSection() ?>

<?= $this->section('estilos'); ?>
<link rel="stylesheet" href="<?php echo site_url('admin/vendors/select2/select2.min.css'); ?>">
<style>
    .ui-autocomplete {
        z-index: 2000;
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
                <?php if (session()->has('errors_model')): ?>
                    <ul>
                        <?php foreach (session('errors_model') as $error): ?>
                            <li class="text-danger">
                                <?= ($error) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <?php echo form_open("admin/produtos/cadastrarextras/$produto->id"); ?>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Escolha o extra do produto(opcional)</label>
                        <select class="form-control js-example-basic-single" name="extra_id">
                            <option value="">Escolha...</option>
                            <?php foreach ($extras as $extra): ?>
                                <option value="<?= $extra->id; ?>">
                                    <?= esc($extra->nome); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="card-footer bg-primary d-flex justify-content-start mt-3">
                        <button type="submit" class="btn btn-primary btn-sm btn-icon-text mr-2">
                            <i class="mdi mdi-content-save btn-icon-prepend"></i>
                            Inserir extra
                        </button>
                        <a href="<?= site_url("admin/produtos/show/$produto->id"); ?>"
                            class="btn btn-light btn-sm btn-icon-text">
                            <i class="mdi mdi-arrow-left btn-icon-prepend"></i> Voltar</a>
                    </div>
                </div>
                <?php echo form_close(); ?>
                <hr class="mt-3">
                <div class="form-row mt-4">
                    <?php if (empty($produtosExtras)): ?>
                        <p>Esse produto não possui extras cadastrados.</p>
                    <?php else: ?>
                        <h4 class="card-title">EXTRAS DO PRODUTO</h4>
                        <p class="card-description">
                            <code>Aproveite para gerenciar os extras</code>
                        </p>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Extra</th>
                                        <th>Preço</th>
                                        <th class="text-center">Remover</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($produtosExtras as $extraProduto): ?>
                                        <tr>
                                            <td><?= esc($extraProduto->extra); ?></td>
                                            <td>R$
                                                <?= esc(number_format($extraProduto->preco, 2, ',', '.')); ?>
                                            </td>
                                            <?php echo form_open("admin/produtos/excluirextra/$extraProduto->id/$extraProduto->produto_id"); ?>
                                            <td class="text-center">
                                                <button type="submit" class="btn btn-danger btn-sm">&nbsp;X&nbsp;</button>
                                            </td>
                                            <?php echo form_close(); ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <div class="mt-3">
                                <?= $pager->links(); ?>
                            </div>
                        </div>

                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?= $this->endSection() ?>

        <!-- Aqui enviamos para o template principal os scripts -->
        <?= $this->section('scripts'); ?>

        <script src="<?php echo site_url('admin/vendors/select2/select2.min.js'); ?>"></script>
        <script>
            $(document).ready(function () {
                $('.js-example-basic-single').select2({
                    placeholder: 'Digite o nome do extra...',
                    allowClear: false,
                    language: {
                        noResults: function () {
                            // Correção: tudo agora é retornado como uma única string válida no JavaScript
                            return 'Extra não encontrado <a class="btn btn-primary btn-sm btn-icon-text mr-2" href="<?php echo site_url('admin/extras/criar'); ?>">Cadastrar</a>';
                        }
                    },
                    escapeMarkup: function (markup) {
                        // Necessário para que o Select2 renderize o botão HTML ao invés de texto
                        return markup;
                    }
                });
            });
        </script>
        <script src="<?php echo site_url('/admin/vendors/mask/jquery.mask.min.js'); ?>"></script>
        <script src="<?php echo site_url('/admin/vendors/mask/app.js'); ?>"></script>

        <?= $this->endSection(); ?>