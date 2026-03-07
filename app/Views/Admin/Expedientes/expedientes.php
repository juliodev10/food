<?= $this->extend('Admin/layout/principal'); ?>


<?= $this->section('titulo'); ?>
<?= $titulo; ?>
<?= $this->endSection() ?>

<?= $this->section('conteudo'); ?>

<div class="row">

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header bg-primary pb-0 pt-4">
                <h4 class="card-title text-white"><?= ($titulo); ?></h4>
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
                <?php echo form_open("admin/expedientes/expedientes", ["class" => "form-row"]); ?>
                <?= csrf_field() ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Dia</th>
                                <th>Abertura</th>
                                <th>Fechamento</th>
                                <th>Situação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($expedientes as $dia): ?>
                                <tr>
                                    <td class="form-group col-md-3">
                                        <input type="text" name="dia_descricao[]" class="form-control"
                                            value="<?= esc($dia->dia_descricao); ?>" readonly="">
                                    </td>
                                    <td class="form-group col-md-3">
                                        <input type="time" name="abertura[]" class="form-control"
                                            value="<?= esc($dia->abertura); ?>" required="">
                                    </td>
                                    <td class="form-group col-md-3">
                                        <input type="time" name="fechamento[]" class="form-control"
                                            value="<?= esc($dia->fechamento); ?>" required="">
                                    </td>
                                    <td class="form-group col-md-3">
                                        <select class="form-control" name="situacao[]" required="">
                                            <option value="1" <?php echo ($dia->situacao == true ? 'selected' : ''); ?>>Aberto
                                            </option>
                                            <option value="0" <?php echo ($dia->situacao == false ? 'selected' : ''); ?>>
                                                Fechado
                                            </option>
                                        </select>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-primary d-flex justify-content-start mt-3">
                    <button type="submit" class="btn btn-primary btn-sm btn-icon-text mr-2">
                        <i class="mdi mdi-content-save btn-icon-prepend"></i>
                        Salvar
                    </button>
                    <a href="<?= site_url("admin/home"); ?>" class="btn btn-light btn-sm btn-icon-text">
                        <i class="mdi mdi-arrow-left btn-icon-prepend"></i> Voltar</a>
                </div>
                <?php echo form_close(); ?>
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
                    url: "<?= site_url('admin/bairros/procurar') ?>",
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
                                label: "Bairro de Pratápolis não encontrado",
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
                window.location.href = "<?= site_url('admin/bairros/show/') ?>" + ui.item.id;
            }
        });
    });
</script>

<?= $this->endSection() ?>