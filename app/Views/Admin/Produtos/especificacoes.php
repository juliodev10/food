<?= $this->extend('Admin/layout/principal'); ?>


<?= $this->section('titulo'); ?>
<?= $titulo; ?>
<?= $this->endSection() ?>

<?= $this->section('estilos'); ?>
<link rel="stylesheet" href="<?php echo site_url('admin/vendors/select2/select2.min.css'); ?>">
<style>
    :root {
        --campo-altura: calc(2.25rem + 2px);
    }

    .ui-autocomplete {
        z-index: 2000;
    }

    .form-row .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-row .form-group .form-control,
    .form-row .form-group .select2-container .select2-selection--single {
        height: var(--campo-altura) !important;
        border: 1px solid #ced4da;
        border-radius: .25rem;
        background-color: #fff;
        color: #495057;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }

    .form-row .form-group .form-control {
        padding: .375rem .75rem;
    }

    .form-row .form-group .form-control:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 .2rem rgba(0, 123, 255, .25);
    }

    .form-row .form-group .select2-container {
        width: 100% !important;
    }

    .form-row .form-group .select2-container .select2-selection--single {
        display: flex;
        align-items: center;
    }

    .form-row .form-group .select2-container .select2-selection__rendered {
        padding-left: .75rem !important;
        padding-right: 2rem !important;
        width: 100%;
        color: #495057 !important;
        line-height: var(--campo-altura) !important;
    }

    .form-row .form-group .select2-container .select2-selection__arrow {
        height: 100% !important;
        right: .35rem;
    }

    .form-row .form-group .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #6c757d;
    }

    .form-row .form-group .select2-container--default.select2-container--focus .select2-selection--single,
    .form-row .form-group .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 .2rem rgba(0, 123, 255, .25);
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #007bff;
        color: white;
        display: block;
    }
</style>

<?= $this->endSection() ?>

<?= $this->section('conteudo'); ?>

<div class="row">

    <div class="col-lg-12 grid-margin stretch-card">
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
                <?php echo form_open("admin/produtos/cadastrarespecificacoes/$produto->id"); ?>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Escolha a medida do produto <a href="javascript:java" type="button" class=""
                                data-bs-toggle="popover" data-bs-title="Medida do produto"
                                data-bs-content="Exemplo de uso:<br>X-Gula 2 Pães, X-Tudo Duplo">Entenda</a></label>
                        <select class="form-control js-example-basic-single" name="medida_id">
                            <option value="">Escolha...</option>
                            <?php foreach ($medidas as $medida): ?>
                                <option value="<?= $medida->id; ?>">
                                    <?= esc($medida->nome); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="preco">Preço</label>
                        <input type="text" class="money form-control" name="preco" id="preco" placeholder="Preço"
                            value="<?php echo old('preco'); ?>">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Produto customizável <a href="javascript:java" type="button" class=""
                                data-bs-toggle="popover" data-bs-title="Produto meio a meio"
                                data-bs-content="Exemplo de uso:<br> Metade Frango/Metade Filé">Entenda</a></label>
                        <select class="form-control js-customizavel-single" name="customizavel">

                            <option value="">Escolha...</option>

                            <option value="1">Sim</option>
                            <option value="0">Não</option>
                        </select>
                    </div>
                    <div class="card-footer bg-primary d-flex justify-content-start mt-3">
                        <button type="submit" class="btn btn-primary btn-sm btn-icon-text mr-2">
                            <i class="mdi mdi-content-save btn-icon-prepend"></i>
                            Inserir especificação
                        </button>
                        <a href="<?= site_url("admin/produtos/show/$produto->id"); ?>"
                            class="btn btn-light btn-sm btn-icon-text">
                            <i class="mdi mdi-arrow-left btn-icon-prepend"></i> Voltar</a>
                    </div>
                </div>
                <?php echo form_close(); ?>
                <hr class="mt-3">
                <div class="form-row mt-4">
                    <?php if (empty($produtoEspecificacoes)): ?>
                        <div class="alert alert-warning" role="alert">
                            <h4 class="alert-heading">Atenção!</h4>
                            <p class="mb-0">Esse produto não possui especificações cadastradas.Portanto, ele <strong>não
                                    será exibido</strong> como opção de compra na área pública.</p>
                            <hr>
                            <p class="mb-0">Para cadastrar as especificações para o produto <strong>
                                    <?= esc($produto->nome); ?>
                                </strong>,
                                clique no botão "Inserir extra" acima e escolha a medida desejada.</p>
                        </div> <?php else: ?>
                        <h4 class="card-title">ESPECIFICAÇÕES DO PRODUTO</h4>
                        <p class="card-description">
                            <code>Aproveite para gerenciar as especificações</code>
                        </p>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Medida</th>
                                        <th>Preço</th>
                                        <th class="text-center align-middle">Customizável</th>
                                        <th class="text-center">Remover</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($produtoEspecificacoes as $especificacao): ?>
                                        <tr>
                                            <td><?= esc($especificacao->medida); ?></td>
                                            <td>R$
                                                <?= esc(number_format($especificacao->preco, 2, ',', '.')); ?>
                                            </td>
                                            <td class="text-center align-middle">
                                                <?php if ($especificacao->customizavel): ?>
                                                    <i class="mdi mdi-check-circle mdi-24px text-success" title="Sim"></i>
                                                <?php else: ?>
                                                    <i class="mdi mdi-close-circle mdi-24px text-danger" title="Não"></i>
                                                <?php endif; ?>
                                            </td>

                                            <td class="text-center">
                                                <a href="<?= site_url("admin/produtos/excluirespecificacao/$especificacao->id/$especificacao->produto_id"); ?>"
                                                    class="btn btn-danger btn-sm btn-icon-text">&nbsp;X&nbsp;
                                                </a>
                                            </td>
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
        <script src="<?php echo site_url('/admin/vendors/mask/jquery.mask.min.js'); ?>"></script>
        <script src="<?php echo site_url('/admin/vendors/mask/app.js'); ?>"></script>

        <script src="<?php echo site_url('admin/vendors/select2/select2.min.js'); ?>"></script>
        <script>
            $(document).ready(function () {
                const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
                const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl, { placement: 'top', html: true }));
                $('.js-example-basic-single').select2({
                    width: '100%',
                    placeholder: 'Digite o nome da medida...',
                    allowClear: false,
                    language: {
                        noResults: function () {
                            // Correção: tudo agora é retornado como uma única string válida no JavaScript
                            return 'Medida não encontrada <a class="btn btn-primary btn-sm btn-icon-text mr-2" href="<?php echo site_url('admin/medidas/criar'); ?>">Cadastrar</a>';
                        }
                    },
                    escapeMarkup: function (markup) {
                        // Necessário para que o Select2 renderize o botão HTML ao invés de texto
                        return markup;
                    }
                });

                $('.js-customizavel-single').select2({
                    width: '100%',
                    placeholder: 'Escolha...',
                    allowClear: false,
                    minimumResultsForSearch: Infinity
                });
            });
        </script>
        <script src="<?php echo site_url('/admin/vendors/mask/jquery.mask.min.js'); ?>"></script>
        <script src="<?php echo site_url('/admin/vendors/mask/app.js'); ?>"></script>

        <?= $this->endSection(); ?>