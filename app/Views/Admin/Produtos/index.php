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
                        placeholder="Pesquise por produto" />
                </div>

                <a href="<?= site_url("admin/produtos/criar"); ?>" class="btn btn-success btn-sm
                    btn-icon-text float-right mb-4">
                    <i class="mdi mdi-plus btn-icon-prepend"></i> Cadastrar</a>

                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Imagem</th>
                                <th>Nome</th>
                                <th>Categoria</th>
                                <th>Data de criação</th>
                                <th>Expecificações</th>
                                <th>Ativo</th>
                                <th>Situação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($produtos as $produto): ?>
                                <tr>
                                    <td class="py-1">
                                        <?php
                                        $caminhoImagemProduto = WRITEPATH . 'uploads/produtos/' . $produto->imagem;
                                        $temImagemValida = !empty($produto->imagem) && is_file($caminhoImagemProduto);
                                        ?>

                                        <?php if ($temImagemValida): ?>
                                            <img src="<?php echo site_url("admin/produtos/imagem/$produto->imagem"); ?>"
                                                alt="<?= esc($produto->nome) ?>" />
                                        <?php else: ?>
                                            <img src="<?php echo site_url('admin/images/Produto-sem-imagem.png'); ?>"
                                                alt="Produto sem imagem" />
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a
                                            href="<?= site_url('admin/produtos/show/' . $produto->id); ?>"><?= $produto->nome; ?></a>
                                    </td>
                                    <td><?= esc($produto->categoria); ?></td>
                                    <td><?= esc($produto->criado_em->humanize()); ?></td>
                                    <td>
                                        <?php foreach ($especificacoes as $especificacao): ?>
                                            <?php if ($especificacao->produto_id === $produto->id): ?>
                                                <span class="badge badge-info"><?= esc($especificacao->descricao); ?>:
                                                    R$: <?= esc($especificacao->preco); ?>
                                                </span>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </td>
                                    <td><?= ($produto->ativo && $produto->deletado_em === null ? '<label class="badge badge-primary">Sim</label>' : '<label class="badge badge-danger">Não</label>'); ?>
                                    </td>
                                    <td><?= ($produto->deletado_em === null ? '<label class="badge badge-success">Disponível</label>' : '<label class="badge badge-danger">Excluído</label>'); ?>
                                        <?php if ($produto->deletado_em !== null): ?>
                                            <a href="<?= site_url("admin/produtos/desfazerExclusao/$produto->id"); ?>"
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
                    url: "<?= site_url('admin/produtos/procurar') ?>",
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
                                label: "Produto não encontrado",
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
                window.location.href = "<?= site_url('admin/produtos/show/') ?>" + ui.item.id;
            }
        });
    });
</script>

<?= $this->endSection() ?>