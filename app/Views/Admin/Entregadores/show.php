<?= $this->extend('Admin/layout/principal'); ?>


<?= $this->section('titulo'); ?>
<?= $titulo; ?>
<?= $this->endSection() ?>

<?= $this->section('estilos'); ?>

<style>
    .ui-autocomplete {
        z-index: 2000;
    }

    .col-card-entregador {
        max-width: 360px;
    }

    @media (min-width: 1200px) {
        .col-card-entregador {
            flex: 0 0 245px !important;
            width: 345px !important;
            max-width: 345px !important;
        }
    }

    .card-entregador-compacto .card-header {
        padding-top: 1rem !important;
        padding-bottom: .8rem !important;
        text-align: center;
    }

    .card-entregador-compacto .card-title {
        font-size: 1.18rem;
        line-height: 1.35;
        margin-bottom: 0;
    }

    .card-entregador-compacto .card-body {
        padding: .8rem;
    }

    .card-entregador-compacto .card-body .card-text {
        font-size: 1rem;
        margin-bottom: .55rem;
    }

    .card-entregador-compacto .card-footer {
        padding: .75rem;
        gap: .55rem;
    }

    .card-entregador-compacto .card-footer .btn {
        min-width: 112px;
        font-size: .96rem;
        padding: .5rem .75rem;
    }

    .card-entregador-compacto .btn-editar-imagem {
        font-size: 1rem;
        padding: .55rem .9rem;
        min-width: 120px;
    }

    @media (min-width: 992px) and (max-width: 1140px) {
        .col-card-entregador {
            flex: 0 0 83.333333%;
            max-width: 83.333333%;
        }
    }
</style>

<?= $this->endSection() ?>

<?= $this->section('conteudo'); ?>

<div class="row justify-content-center">

    <div class="col-lg-4 col-md-6 col-sm-8 grid-margin stretch-card col-card-entregador">
        <div class="card w-100 card-entregador-compacto">
            <div class="card-header bg-primary">
                <h4 class="card-title text-white">
                    <?= esc($titulo); ?>
                </h4>
            </div>
            <div class="card-body">
                <div class="card mx-auto" style="width: 20.4rem;">
                    <?php
                    $caminhoImagementregador = WRITEPATH . 'uploads/entregadores/' . $entregador->imagem;
                    $temImagemValida = !empty($entregador->imagem)
                        && empty($entregador->deletado_em)
                        && is_file($caminhoImagementregador);
                    ?>
                    <?php if ($temImagemValida): ?>
                        <img class="card-img-top"
                            src="<?php echo site_url("admin/entregadores/imagem/$entregador->imagem"); ?>"
                            alt="<?= esc($entregador->nome); ?>">
                    <?php else: ?>
                        <img class="card-img-top mx-auto" style="max-width: 90%"
                            src="<?php echo site_url('admin/images/entregador-sem-imagem.webp'); ?>"
                            alt="entregador sem imagem">
                    <?php endif; ?>

                    <?php if ($entregador->deletado_em == null): ?>
                        <hr>
                        <div class="p-2">
                            <a href="<?= site_url("admin/entregadores/editarimagem/{$entregador->id}"); ?>"
                                class="btn btn-light btn-icon-text btn-editar-imagem">
                                <i class="mdi mdi-image btn-icon-prepend"></i> Editar
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <p class="card-text text-center">
                    <span class="font-weight-bold">Nome:</span>
                    <?= esc($entregador->nome); ?>
                </p>
                <p class="card-text text-center">
                    <span class="font-weight-bold">Telefone:</span>
                    <?= esc($entregador->telefone); ?>
                </p>
                <p class="card-text text-center">
                    <span class="font-weight-bold">Veículo:</span>''
                    <?= esc($entregador->veiculo); ?> | <?= esc($entregador->placa); ?>
                </p>
                <p class="card-text text-center">
                    <span class="font-weight-bold">Ativo:</span>
                    <?= esc($entregador->ativo ? 'Sim' : 'Não'); ?>
                </p>
                <p class="card-text text-center">
                    <span class="font-weight-bold">Criado:</span>
                    <?= esc($entregador->criado_em->humanize()); ?>
                </p>
                <?php if ($entregador->deletado_em == null): ?>
                    <p class="card-text text-center">
                        <span class="font-weight-bold">Atualizado:</span>
                        <?= esc($entregador->atualizado_em->humanize()); ?>
                    </p>
                <?php else: ?>
                    <p class="card-text text-center">
                        <span class="font-weight-bold text-danger">Excluído:</span>
                        <?= esc($entregador->deletado_em->humanize()); ?>
                    </p>
                <?php endif; ?>
                <div class="mt-3">
                    <?php if ($entregador->deletado_em == null): ?>
                        <div class="card-footer bg-primary d-flex justify-content-center flex-wrap">
                            <a href="<?= site_url("admin/entregadores"); ?>" class="btn btn-light btn-icon-text mr-2">
                                <i class="mdi mdi-arrow-left btn-icon-prepend"></i> Voltar
                            </a>
                            <a href="<?= site_url("admin/entregadores/editar/{$entregador->id}"); ?>"
                                class="btn btn-warning btn-icon-text mr-2">
                                <i class="mdi mdi-pencil btn-icon-prepend"></i> Editar
                            </a>
                            <a href="<?= site_url("admin/entregadores/excluir/{$entregador->id}"); ?>"
                                class="btn btn-danger btn-icon-text">
                                <i class="mdi mdi-delete btn-icon-prepend"></i> Excluir
                            </a>
                        <?php else: ?>
                            <a href="<?= site_url("admin/entregadores"); ?>" class="btn btn-light btn-icon-text mr-2">
                                <i class="mdi mdi-arrow-left btn-icon-prepend"></i> Voltar
                            </a>
                            <a href="<?= site_url("admin/entregadores/desfazerExclusao/{$entregador->id}"); ?>" class="btn btn-info
                            btn-icon-text ml-2" data-bs-toggle="tooltip" data-bs-placement="top"
                                data-bs-title="Desfazer alteração">
                                <i class="mdi mdi-undo btn-icon-prepend"></i> Desfazer
                            </a>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <?= $this->endSection() ?>
    <!-- Aqui enviamos para o template principal os scripts -->
    <?= $this->section('scripts'); ?>
    <script src="<?php echo site_url('admin/vendors/auto-complete/jquery-ui.js'); ?>"></script>
    <script>
        // Inicializa todos os tooltips da página
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    </script>

    <?= $this->endSection() ?>