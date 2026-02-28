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
                <form method="POST" action="<?= site_url("admin/entregadores/upload/$entregador->id"); ?>"
                    enctype="multipart/form-data">
                    <?= csrf_field(); ?>
                    <div class="form-group mb-3">
                        <label>Upload de imagem</label>
                        <input type="file" id="foto_entregador" name="foto_entregador" class="file-upload-default"
                            accept="image/*">
                        <div class="input-group col-xs-12">
                            <input type="text" class="form-control file-upload-info" disabled
                                placeholder="Escolha uma imagem (máx. 9MB)">
                            <span class="input-group-append">
                                <button class="file-upload-browse btn btn-primary" type="button">Escolher</button>
                            </span>
                        </div>
                        <small class="form-text text-muted">Tamanho máximo permitido: 9MB.</small>
                    </div>

                    <div class="card-footer bg-primary d-flex justify-content-start mt-3">
                        <button type="submit" class="btn btn-primary btn-sm btn-icon-text mr-2">
                            <i class="mdi mdi-content-save btn-icon-prepend"></i>
                            Salvar
                        </button>
                        <a href="<?= site_url("admin/entregadores/show/$entregador->id"); ?>"
                            class="btn btn-light btn-sm btn-icon-text">
                            <i class="mdi mdi-arrow-left btn-icon-prepend"></i> Voltar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?= $this->endSection() ?>

    <!-- Aqui enviamos para o template principal os scripts -->
    <?= $this->section('scripts'); ?>
    <script src="<?php echo site_url('/admin/vendors/mask/jquery.mask.min.js'); ?>"></script>
    <script src="<?php echo site_url('/admin/vendors/mask/app.js'); ?>"></script>
    <script src="<?php echo site_url('admin/js/file-upload.js'); ?>"></script>

    <?= $this->endSection() ?>