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
                        placeholder="Pesquise por um código de pedido" />
                </div>
                <?php if (empty($pedidos)): ?>
                    <p>Nenhum pedido encontrado.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>Código do pedido</th>
                                    <th>Data do pedido</th>
                                    <th>Cliente</th>
                                    <th>Valor</th>
                                    <th>Situação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pedidos as $pedido): ?>
                                    <tr>
                                        <td>
                                            <a
                                                href="<?= site_url('admin/pedidos/show/' . $pedido->codigo); ?>"><?= $pedido->codigo; ?></a>
                                        </td>
                                        <td><?= esc($pedido->criado_em->humanize()); ?></td>
                                        <td><?= esc($pedido->cliente); ?></td>
                                        <td>R$&nbsp;<?= esc(number_format($pedido->valor_total, 2, ',', '.')); ?></td>
                                        <td>
                                            <?= ($pedido->deletado_em === null ? $pedido->exibeSituacaoDoPedido() : '<label class="badge badge-danger">Excluído</label>'); ?>
                                            <?php if ($pedido->deletado_em !== null): ?>
                                                <a href="<?= site_url('admin/pedidos/desfazerExclusao/' . $pedido->codigo); ?>"
                                                    class="btn btn-info btn-sm btn-icon-text ml-2">
                                                    <i class="mdi mdi-undo btn-icon-prepend"></i> Desfazer</a>
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
                    url: "<?= site_url('admin/pedidos/procurar') ?>",
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
                                label: "Pedido não encontrado",
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
                window.location.href = "<?= site_url('admin/pedidos/show/') ?>" + ui.item.value;
            }
        });
    });
</script>

<?= $this->endSection() ?>