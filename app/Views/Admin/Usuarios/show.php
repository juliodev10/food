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
                <p class="card-text">
                    <span class="font-weight-bold">Nome:</span>
                    <?= esc($usuario->nome); ?>
                </p>
                <p class="card-text">
                    <span class="font-weight-bold">Email:</span>
                    <?= esc($usuario->email); ?>
                </p>
                <p class="card-text">
                    <span class="font-weight-bold">Ativo:</span>
                    <?= esc($usuario->ativo ? 'Sim' : 'Não'); ?>
                </p>
                <p class="card-text">
                    <span class="font-weight-bold">Perfil:</span>
                    <?= esc($usuario->is_admin ? 'Administrador' : 'Cliente'); ?>
                </p>
                <p class="card-text">
                    <span class="font-weight-bold">Criado:</span>
                    <?= esc($usuario->criado_em->humanize()); ?>
                </p>
                <p class="card-text">
                    <span class="font-weight-bold">Atualizado:</span>
                    <?= esc($usuario->atualizado_em->humanize()); ?>
                </p>
            </div>
            <div class="card-footer bg-primary d-flex justify-content-start">
                <a href="<?= site_url("admin/usuarios"); ?>" class="btn btn-light btn-sm btn-icon-text mr-2">
                    <i class="mdi mdi-arrow-left btn-icon-prepend"></i> Voltar
                </a>
                <a href="<?= site_url("admin/usuarios/editar/$usuario->id"); ?>"
                    class="btn btn-warning btn-sm btn-icon-text mr-2">
                    <i class="mdi mdi-pencil btn-icon-prepend"></i> Editar
                </a>
                <a href="<?= site_url("admin/usuarios/excluir/$usuario->id"); ?>"
                    class="btn btn-danger btn-sm btn-icon-text"
                    onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                    <i class="mdi mdi-delete btn-icon-prepend"></i> Excluir
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<!-- Aqui enviamos para o template principal os scripts -->
<?= $this->section('scripts'); ?>
<script src="<?php echo site_url('admin/vendors/auto-complete/jquery-ui.js'); ?>"></script>

<?= $this->endSection() ?>