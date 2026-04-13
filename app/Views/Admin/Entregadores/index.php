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
            <div class="card-header bg-primary pb-0 pt-4">
                <h4 class="card-title text-white"><?= ($titulo); ?></h4>
            </div>
            <div class="card-body">
                <div class="ui-widget">
                    <input id="query" name="query" class="form-control bg-light mb-4"
                        placeholder="Pesquise por um entregador" />
                </div>

                <a href="<?= site_url("admin/entregadores/criar"); ?>" class="btn btn-success btn-sm
                    btn-icon-text float-right mb-4">
                    <i class="mdi mdi-plus btn-icon-prepend"></i> Cadastrar</a>

                <?php if (empty($entregadores)): ?>
                    <div class="alert alert-info">
                        <p>Não há entregadores cadastrados.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Imagem</th>
                                    <th>Nome</th>
                                    <th>Telefone</th>
                                    <th>Placa</th>
                                    <th>Ativo</th>
                                    <th>Situação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($entregadores as $entregador): ?>
                                    <tr>
                                        <td class="py-1">
                                            <?php
                                            $caminhoImagemEntregador = WRITEPATH . 'uploads/entregadores/' . $entregador->imagem;
                                            $temImagemValida = !empty($entregador->imagem) && is_file($caminhoImagemEntregador);
                                            ?>
                                            <?php if ($temImagemValida): ?>
                                                <img src="<?php echo site_url("admin/entregadores/imagem/$entregador->imagem"); ?>"
                                                    alt="<?= esc($entregador->nome) ?>" />
                                            <?php else: ?>
                                                <img src="<?php echo site_url('admin/images/entregador-sem-imagem.webp'); ?>"
                                                    alt="Entregador sem imagem" />
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a
                                                href="<?= site_url('admin/entregadores/show/' . $entregador->id); ?>"><?= $entregador->nome; ?></a>
                                        </td>
                                        <td><?= $entregador->telefone; ?></td>
                                        <td><?= $entregador->placa; ?></td>

                                        <td><?= ($entregador->ativo && $entregador->deletado_em === null ? '<label class="badge badge-primary">Sim</label>' : '<label class="badge badge-danger">Não</label>'); ?>
                                        </td>
                                        <td><?= ($entregador->deletado_em === null ? '<label class="badge badge-success">Disponível</label>' : '<label class="badge badge-danger">Excluído</label>'); ?>
                                            <?php if ($entregador->deletado_em !== null): ?>
                                                <a href="<?= site_url("admin/entregadores/desfazerExclusao/$entregador->id"); ?>"
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

                <?php endif; ?>


            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<!-- Aqui enviamos para o template principal os scripts -->
<?= $this->section('scripts'); ?>
<script src="<?php echo site_url('admin/vendors/auto-complete/jquery-ui.js'); ?>"></script>

<script>
    jQuery(function($) {
        $("#query").autocomplete({
            appendTo: "body",
            minLength: 1,
            source: function(request, response) {
                $.ajax({
                    url: "<?= site_url('admin/entregadores/procurar') ?>",
                    dataType: "json",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        if (!data || data.length < 1) {
                            response([{
                                label: "Entregador não encontrado",
                                value: -1
                            }]);
                            return;
                        }
                        response(data);
                    },
                    error: function(xhr, status, error) {
                        console.error("Erro na requisição:", status, error);
                        response([]);
                    }
                });
            },
            select: function(event, ui) {
                if (ui.item.value == -1) {
                    $(this).val("");
                    return false;
                }
                window.location.href = "<?= site_url('admin/entregadores/show/') ?>" + ui.item.id;
            }
        });
    });
</script>

<?= $this->endSection() ?>