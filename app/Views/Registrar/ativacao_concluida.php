<?= $this->extend('Admin/layout/principal_autenticacao'); ?>

<?= $this->section('titulo'); ?>
<?= $titulo; ?>
<?= $this->endSection() ?>

<?= $this->section('estilos'); ?>
<style>
    .ativacao-page {
        min-height: 100%;
        display: flex;
        flex-direction: column;
    }

    .ativacao-page .page-body-wrapper.full-page-wrapper {
        flex: 1 1 auto;
        display: flex;
    }

    .ativacao-page .content-wrapper.auth.auth-img-bg {
        flex: 1 1 auto;
        min-height: 0;
    }

    .ativacao-page .row.flex-grow {
        width: 100%;
        margin: 0;
    }

    .ativacao-card {
        background: #fff;
        border-radius: 10px;
        padding: 24px;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
        max-width: 420px;
    }

    .ativacao-card h4 {
        margin-bottom: 10px;
    }

    .ativacao-card p {
        color: #555;
        margin-bottom: 14px;
        max-width: 30ch;
    }

    @media (max-width: 991.98px) {
        .ativacao-page {
            overflow-x: hidden;
        }

        .ativacao-page .auth-form-transparent {
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
            padding: 0.75rem !important;
            box-sizing: border-box;
        }

        .ativacao-card {
            width: 100%;
            max-width: 100%;
            padding: 18px;
            min-height: auto;
            box-sizing: border-box;
        }

        .ativacao-card p {
            width: 100%;
            max-width: 100%;
            white-space: normal !important;
            overflow-wrap: break-word;
            word-break: normal;
            hyphens: auto;
        }

        .ativacao-page .col-lg-6 {
            padding-left: 12px;
            padding-right: 12px;
        }
    }

    @media (min-width: 992px) {
        .ativacao-page .auth-form-transparent {
            width: 100%;
            max-width: 560px;
        }

        .ativacao-card {
            padding: 32px;
            min-height: 260px;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('conteudo'); ?>
<div class="ativacao-page">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
            <div class="row flex-grow">
                <div class="col-lg-6 d-flex align-items-center justify-content-center">
                    <div class="auth-form-transparent text-left p-3">
                        <div class="brand-logo">
                            <img src="<?= site_url('admin/'); ?>images/logo.svg" alt="logo">
                        </div>

                        <div class="ativacao-card">
                            <h4><?= esc($titulo) ?></h4>
                            <p>
                                Sua conta foi ativada com sucesso.
                            </p>
                            <p class="mb-4">
                                Agora voce ja pode entrar e fazer seu primeiro pedido.
                            </p>

                            <a href="<?= site_url('login'); ?>" class="btn btn-primary btn-lg font-weight-medium">
                                Ir para o login
                            </a>
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
<?= $this->endSection() ?>