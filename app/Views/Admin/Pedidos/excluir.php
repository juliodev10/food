<?= $this->extend('Admin/layout/principal'); ?>


<?= $this->section('titulo'); ?>
<?= $titulo; ?>
<?= $this->endSection() ?>

<?= $this->section('estilos'); ?>

<style>
    .ui-autocomplete {
        z-index: 2000;
    }
</style>

<?= $this->endSection() ?>

<?= $this->section('conteudo'); ?>

<div class="row">

    <div class="col-lg-8 grid-margin stretch-card">
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
                <?php echo form_open("admin/pedidos/excluir/$pedido->codigo"); ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Atenção!</strong> Tem certeza que deseja excluir o pedido
                    <strong>
                        <?= esc($pedido->codigo); ?>
                    </strong>?
                </div>
                <button type="submit" class="btn btn-danger btn-sm btn-icon-text mr-2">
                    <i class="mdi mdi-trash-can btn-icon-prepend"></i>
                    Excluir
                </button>

                <?= csrf_field() ?>
                <a href="<?= site_url("admin/pedidos/show/$pedido->codigo"); ?>" class="btn btn-light btn-sm
                    btn-icon-text">
                    <i class="mdi mdi-arrow-left btn-icon-prepend"></i> Voltar</a>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>

    <?= $this->endSection() ?>

    <!-- Aqui enviamos para o template principal os scripts -->
    <?= $this->section('scripts'); ?>
    <script src="<?php echo site_url('/admin/vendors/mask/jquery.mask.min.js'); ?>"></script>
    <script src="<?php echo site_url('/admin/vendors/mask/app.js'); ?>"></script>

    <?= $this->endSection() ?>