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

    <div class="col-lg-8 grid-margin stretch-card">
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
                <?php echo form_open("admin/pedidos/atualizar/$pedido->codigo"); ?>
                <div class="form-check form-check-flat form-check-primary mb-4">
                    <label for="saiu_entrega" class="form-check-label">
                        <input type="radio" name="situacao" id="saiu_entrega" value="1" class="form-check-input situacao"
                            <?= ($pedido->situacao == 1) ? 'checked' : ''; ?>>
                        Saiu para entrega
                    </label>
                </div>

                <div id="box_entregador" class="form-group d-none">
                    <select name="entregador_id" id="entregador_id" class="form-control">
                        <option value="">Selecione um entregador</option>
                        <?php foreach ($entregadores as $entregador): ?>
                            <option value="<?= $entregador->id ?>" <?= ($pedido->entregador_id == $entregador->id) ? 'selected' : ''; ?>>
                                <?= $entregador->nome ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-check form-check-flat form-check-primary mb-4">
                    <label for="entregue" class="form-check-label">
                        <input type="radio" name="situacao" id="entregue" value="2" class="form-check-input situacao"
                            <?= ($pedido->situacao == 2) ? 'checked' : ''; ?>>
                        Pedido entregue
                    </label>
                </div>
                <div class="form-check form-check-flat form-check-primary mb-4">
                    <label for="cancelado" class="form-check-label situacao">
                        <input type="radio" name="situacao" id="cancelado" value="3" class="form-check-input situacao"
                            <?= ($pedido->situacao == 3) ? 'checked' : ''; ?>>
                        Pedido cancelado
                    </label>
                </div>

                <?= csrf_field() ?>
                <div class="card-footer bg-primary d-flex justify-content-start mt-3">
                    <button id="btn-editar-pedido" type="submit" class="btn btn-primary btn-sm btn-icon-text mr-2" value="Editar pedido">
                        <i class="mdi mdi-content-save btn-icon-prepend"></i>
                        Salvar
                    </button>
                    <a href="<?= site_url("admin/pedidos/show/$pedido->codigo"); ?>"
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

    <script>
        $(document).ready(function() {
            var entregador_id = $("#saiu_entrega").prop('checked');
            if (entregador_id) {
                $('#box_entregador').removeClass('d-none');
            }
            $('.situacao').on('click', function() {
                var valor = $(this).val();
                if (valor == '1') {
                    $('#box_entregador').removeClass('d-none');
                } else {
                    $('#box_entregador').addClass('d-none');
                }
            });
            $("form").submit(function() {
                $(this).find(":submit").attr('disabled', 'disabled');
                $("#btn-editar-pedido").val('Processando...');
            })
        });
    </script>

    <?= $this->endSection() ?>