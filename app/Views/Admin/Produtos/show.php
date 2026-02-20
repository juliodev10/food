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
                <div class="card" style="width: 18rem;">
                    <?php if ($produto->imagem): ?>
                        <img class="card-img-top" src="..." alt="Card iamge cap">
                    <?php else: ?>
                        <img class="card-img-top mx-auto" style="max-width: 80%"
                            src="<?php echo site_url('admin/images/Produto-sem-imagem.png'); ?>" alt="Produto sem imagem">
                    <?php endif; ?>

                    <div class="p-2">
                        <a href="<?= site_url("admin/produtos/editarimagem/$produto->id"); ?>"
                            class="btn btn-light btn-sm btn-icon-text">
                            <i class="mdi mdi-image btn-icon-prepend"></i> Editar
                        </a>
                    </div>
                </div>

                <p class="card-text">
                    <span class="font-weight-bold">Nome:</span>
                    <?= esc($produto->nome); ?>
                </p>
                <p class="card-text">
                    <span class="font-weight-bold">Categoria:</span>
                    <?= esc($produto->categoria); ?>
                </p>
                <p class="card-text">
                    <span class="font-weight-bold">Slug:</span>''
                    <?= esc($produto->slug); ?>
                </p>
                <p class="card-text">
                    <span class="font-weight-bold">Ativo:</span>
                    <?= esc($produto->ativo ? 'Sim' : 'Não'); ?>
                </p>
                <p class="card-text">
                    <span class="font-weight-bold">Criado:</span>
                    <?= esc($produto->criado_em->humanize()); ?>
                </p>
                <?php if ($produto->deletado_em == null): ?>
                    <p class="card-text">
                        <span class="font-weight-bold">Atualizado:</span>
                        <?= esc($produto->atualizado_em->humanize()); ?>
                    </p>
                <?php else: ?>
                    <p class="card-text">
                        <span class="font-weight-bold text-danger">Excluído:</span>
                        <?= esc($produto->deletado_em->humanize()); ?>
                    </p>
                <?php endif; ?>
                <div class="mt-4">
                    <?php if ($produto->deletado_em == null): ?>
                        <div class="card-footer bg-primary d-flex justify-content-start">
                            <a href="<?= site_url("admin/produtos"); ?>" class="btn btn-light btn-sm btn-icon-text mr-2">
                                <i class="mdi mdi-arrow-left btn-icon-prepend"></i> Voltar
                            </a>
                            <a href="<?= site_url("admin/produtos/editar/$produto->id"); ?>"
                                class="btn btn-warning btn-sm btn-icon-text mr-2">
                                <i class="mdi mdi-pencil btn-icon-prepend"></i> Editar
                            </a>
                            <a href="<?= site_url("admin/produtos/excluir/$produto->id"); ?>"
                                class="btn btn-danger btn-sm btn-icon-text">
                                <i class="mdi mdi-delete btn-icon-prepend"></i> Excluir
                            </a>
                        <?php else: ?>
                            <a href="<?= site_url("admin/produtos"); ?>" class="btn btn-light btn-sm btn-icon-text mr-2">
                                <i class="mdi mdi-arrow-left btn-icon-prepend"></i> Voltar
                            </a>
                            <a href="<?= site_url("admin/produtos/desfazerExclusao/$produto->id"); ?>" class="btn btn-info
                            btn-sm btn-icon-text ml-2" data-bs-toggle="tooltip" data-bs-placement="top"
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