<?= $this->extend('Admin/layout/principal'); ?>


<?= $this->section('titulo'); ?>
<?= $titulo; ?>
<?= $this->endSection() ?>

<?= $this->section('estilos'); ?>
<!-- Aqui enviamos para o template principal os estilos -->

<link rel="stylesheet" href="<?php echo site_url('admin/vendors/auto-complete/jquery-ui.css'); ?>" />
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
            <div class="card-body">
                <h4 class="card-title"><?= ($titulo); ?></h4>

                <div class="ui-widget">
                    <input id="query" name="query" class="form-control bg-light mb-4"
                        placeholder="Pesquise por um usuário" />
                </div>

                <a href="<?= site_url("admin/usuarios/criar"); ?>" class="btn btn-success btn-sm
                    btn-icon-text float-right mb-4">
                    <i class="mdi mdi-plus btn-icon-prepend"></i> Cadastrar</a>

                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>CPF</th>
                                <th>Ativo</th>
                                <th>Situação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td>
                                        <a
                                            href="<?= site_url('admin/usuarios/show/' . $usuario->id); ?>"><?= $usuario->nome; ?></a>
                                    </td>
                                    <td><?= $usuario->email; ?></td>
                                    <td><?= $usuario->cpf; ?></td>

                                    <td><?= ($usuario->ativo && $usuario->deletado_em === null ? '<label class="badge badge-primary">Sim</label>' : '<label class="badge badge-danger">Não</label>'); ?>
                                    </td>
                                    <td><?= ($usuario->deletado_em === null ? '<label class="badge badge-success">Disponível</label>' : '<label class="badge badge-danger">Excluído</label>'); ?>
                                        <?php if ($usuario->deletado_em !== null): ?>
                                            <a href="<?= site_url("admin/usuarios/desfazerExclusao/$usuario->id"); ?>"
                                                class="btn btn-info btn-sm btn-icon-text ml-2">
                                                <i class=" mdi mdi-undo btn-icon-prepend"></i> Desfazer</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                    <div class="mt-3">
                        <?= $pager->links(); ?>
                    </div>
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
    jQuery(function ($) {
        $("#query").autocomplete({
            appendTo: "body",
            minLength: 1,
            source: function (request, response) {
                $.ajax({
                    url: "<?= site_url('admin/usuarios/procurar') ?>",
                    dataType: "json",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    data: {
                        term: request.term
                    },
                    success: function (data) {
                        if (!data || data.length < 1) {
                            response([{
                                label: "Usuário não encontrado",
                                value: -1
                            }]);
                            return;
                        }
                        response(data);
                    },
                    error: function (xhr, status, error) {
                        console.error("Erro na requisição:", status, error);
                        response([]);
                    }
                });
            },
            select: function (event, ui) {
                if (ui.item.value == -1) {
                    $(this).val("");
                    return false;
                }
                window.location.href = "<?= site_url('admin/usuarios/show/') ?>" + ui.item.id;
            }
        });
    });
</script>

<?= $this->endSection() ?>