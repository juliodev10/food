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
                <?php echo form_open("admin/categorias/atualizar/$categoria->id"); ?>
                <?= csrf_field() ?>
                <?php echo $this->include('Admin/Categorias/form'); ?>
                <div class="card-footer bg-primary d-flex justify-content-start mt-3">
                    <button type="submit" class="btn btn-primary btn-sm btn-icon-text mr-2">
                        <i class="mdi mdi-content-save btn-icon-prepend"></i>
                        Salvar
                    </button>
                    <a href="<?= site_url("admin/categorias/show/$categoria->id"); ?>"
                        class="btn btn-light btn-sm btn-icon-text">
                        <i class="mdi mdi-arrow-left btn-icon-prepend"></i> Voltar</a>
                </div>
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