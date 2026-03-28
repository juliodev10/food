<?= $this->extend('Admin/layout/principal_autenticacao'); ?>

<?= $this->section('titulo'); ?>
<?= $titulo; ?>

<?= $this->endSection() ?>

<?= $this->section('estilos'); ?>
<link rel="stylesheet" href="<?= base_url('admin/css/footer.css') ?>">
<style>
    .registro-page {
        min-height: 100%;
        display: flex;
        flex-direction: column;
    }

    .registro-page .page-body-wrapper.full-page-wrapper {
        flex: 1 1 auto;
        display: flex;
    }

    .registro-page .content-wrapper.auth.auth-img-bg {
        flex: 1 1 auto;
        min-height: 0;
    }

    .registro-card {
        background: #fff;
        border-radius: 10px;
        padding: 24px;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
    }

    .registro-card h4 {
        margin-bottom: 8px;
    }

    .registro-card p {
        margin-bottom: 16px;
        color: #555;
    }

    .registro-card .form-control {
        margin-bottom: 12px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('conteudo'); ?>
<div class="registro-page">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
            <div class="row flex-grow">
                <div class="col-lg-6 d-flex align-items-center justify-content-center">
                    <div class="auth-form-transparent text-left p-3">
                        <?php if (session()->has('sucesso')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Perfeito!</strong>
                                <?= session('sucesso'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif ?>

                        <?php if (session()->has('atencao')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Atenção!</strong>
                                <?= session('atencao'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif ?>

                        <?php if (session()->has('errors_model')): ?>
                            <div class="alert alert-danger" role="alert">
                                <strong>Erro na validação!</strong>
                                <ul class="mb-0 mt-2">
                                    <?php foreach (session('errors_model') as $error): ?>
                                        <li><?= ($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <div class="brand-logo">
                            <img src="<?php echo site_url('admin/'); ?>images/logo.svg" alt="logo">
                        </div>

                        <div class="registro-card">
                            <h4>Cadastro</h4>
                            <p>Preencha seus dados para criar a conta.</p>

                            <?php echo form_open('registrar/criar'); ?>
                            <div class="form-group">
                                <label>Nome Completo</label>
                                <input type="text" name="nome" class="form-control form-control-lg" placeholder="Digite seu nome completo" autocomplete="name"
                                    value="<?php echo old('nome'); ?>">
                            </div>

                            <div class="form-group">
                                <label for="email">E-mail válido</label>
                                <input type="email" name="email" class="form-control form-control-lg" placeholder="Digite seu e-mail" autocomplete="email"
                                    value="<?php echo old('email'); ?>">
                            </div>

                            <div class="form-group">
                                <label>CPF (opcional)</label>
                                <input type="text" name="cpf" class="cpf form-control form-control-lg" placeholder="Digite seu CPF" autocomplete="cpf"
                                    value="<?php echo old('cpf'); ?>">
                            </div>

                            <div class="form-group">
                                <label>Senha</label>
                                <input type="password" name="password" class="form-control form-control-lg" placeholder="Digite sua senha" autocomplete="new-password" minlength="6">
                            </div>

                            <div class="form-group">
                                <label>Confirmar senha</label>
                                <input type="password" name="confirmation_password" class="form-control form-control-lg" placeholder="Confirme sua senha"
                                    autocomplete="new-password" minlength="6">
                            </div>

                            <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                                Criar conta
                            </button>
                            <?php echo form_close(); ?>

                            <div class="text-center mt-3 font-weight-light">
                                Já tem conta?
                                <a href="<?php echo site_url('login'); ?>" class="text-primary">Fazer login</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 login-half-bg d-flex flex-row">
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts'); ?>

<script src="<?php echo site_url('admin/vendors/mask/jquery.mask.min.js') ?>"></script>
<script src="<?php echo site_url('admin/vendors/mask/app.js'); ?>"></script>

<?= $this->endSection() ?>