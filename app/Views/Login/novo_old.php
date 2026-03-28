<?= $this->extend('Admin/layout/principal_autenticacao'); ?>

<?= $this->section('titulo'); ?>
<?= $titulo; ?>
<?= $this->endSection() ?>

<?= $this->section('estilos'); ?>
<!-- Aqui enviamos para o template principal os estilos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="<?= base_url('admin/css/footer.css') ?>">
<style>
    .login-page {
        min-height: 100%;
        display: flex;
        flex-direction: column;
    }

    .login-page .page-body-wrapper.full-page-wrapper {
        flex: 1 1 auto;
        display: flex;
    }

    .login-page .content-wrapper.auth.auth-img-bg {
        flex: 1 1 auto;
        min-height: 0;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('conteudo'); ?>
<div class="login-page">
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
                        <?php if (session()->has('info')): ?>
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                <strong class="d-block d-sm-inline">Informação!</strong>
                                <span class="d-block d-sm-inline text-break"><?= session('info'); ?></span>
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
                        <?php if (session()->has('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Erro!</strong>
                                <?= session('error'); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif ?>

                        <div class="brand-logo">
                            <img src="<?php echo site_url('admin/'); ?>images/logo.svg" alt="logo">
                        </div>
                        <h4>Olá, seja bem-vindo(a)!</h4>
                        <h6 class="font-weight-light">Por favor, realize o Login:</h6>
                        <?= form_open('login/criar'); ?>
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label for="exampleInputEmail">Usuário(a)</label>
                            <div class="input-group">
                                <div class="input-group-prepend bg-transparent">
                                    <span class="input-group-text bg-transparent border-right-0">
                                        <i class="mdi mdi-account-outline text-primary"></i>
                                    </span>
                                </div>
                                <input type="email" name="email" value="<?php echo old('email'); ?> "
                                    class="form-control form-control-lg border-left-0" id="exampleInputEmail"
                                    placeholder="Digite seu email:">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputPassword">Senha</label>
                            <div class="input-group">
                                <div class="input-group-prepend bg-transparent">
                                    <span class="input-group-text bg-transparent border-right-0">
                                        <i class="mdi mdi-lock-outline text-primary"></i>
                                    </span>
                                </div>
                                <input type="password" name="password"
                                    class="form-control form-control-lg border-left-0" id="exampleInputPassword"
                                    placeholder="Digite sua senha:">
                            </div>
                        </div>
                        <div class="my-2 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <label class="form-check-label text-muted">
                                    <input type="checkbox" class="form-check-input">
                                    Manter-me conectado
                                </label>
                            </div>
                            <a href="<?php echo site_url('password/esqueci'); ?>" class="auth-link text-black">Esqueceu
                                a senha?</a>
                        </div>
                        <div class="my-3">
                            <button type="submit"
                                class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">ENTRAR</button>
                        </div>

                        <div class="text-center mt-4 font-weight-light">
                            Não tem uma conta? <a href="<?php echo site_url('registrar'); ?>  " class="text-primary">
                                Criar uma</a>
                        </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6 login-half-bg d-flex flex-row">
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
    </div>
</div>
<?= $this->endSection() ?>

<!-- Aqui enviamos para o template principal os scripts -->
<?= $this->section('scripts'); ?>
<?= $this->endSection() ?>