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

    <div class="col-lg-12 grid-margin stretch-card">
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
                <?php echo form_open("admin/bairros/cadastrar"); ?>
                <?php echo $this->include('Admin/Bairros/form'); ?>
                <div class="card-footer bg-primary d-flex justify-content-start mt-3">
                    <button id="btn-salvar" type="submit" class="btn btn-primary btn-sm btn-icon-text mr-2">
                        <i class="mdi mdi-content-save btn-icon-prepend"></i>
                        Salvar
                    </button>
                    <a href="<?= site_url("admin/bairros"); ?>" class="btn btn-light btn-sm btn-icon-text">
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
        $('[name=cep]').focusout(function () {
            var cep = $(this).val();
            var cepNumerico = cep.replace(/\D/g, '');

            if (cepNumerico.length !== 8) {
                $('#cep').html('<span class="text-danger small">Informe um CEP válido.</span>');
                $('[name=nome]').val('');
                $('[name=cidade]').val('');
                $('[name=estado]').val('');
                return;
            }

            $.ajax({
                type: 'get',
                url: '<?php echo site_url("admin/bairros/consultacep"); ?>',
                dataType: 'json',
                data: {
                    cep: cep
                },
                beforeSend: function () {
                    $('#cep').html('Consultando...');
                    $('[name=nome]').val('');
                    $('[name=cidade]').val('');
                    $('[name=estado]').val('');
                },
                success: function (response) {
                    if (!response.erro) {
                        $('#cep').html('');
                        $('[name=nome]').val(response.endereco.bairro || '');
                        $('[name=cidade]').val(response.endereco.localidade);
                        $('[name=estado]').val(response.endereco.uf);
                        if (!response.endereco.bairro) {
                            $('#cep').html('<span class="text-warning small">CEP localizado, mas sem bairro. Informe o nome manualmente.</span>');
                            $('[name=nome]').focus();
                        }
                    } else {
                        $('#cep').html(response.erro);
                    }
                },//fim success
                error: function () {
                    $('#cep').html('<span class="text-danger small">Não foi possível consultar o CEP no momento.</span>');
                },
            });
        });
    </script>

    <?= $this->endSection() ?>