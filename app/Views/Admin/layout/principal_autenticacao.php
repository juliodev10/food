<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Food Delivery |
        <?= $this->renderSection('titulo') ?>
    </title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- plugins:css -->
    <link rel="stylesheet" href="<?php echo site_url('admin/'); ?>vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="<?php echo site_url('admin/'); ?>vendors/base/vendor.bundle.base.css">
    <!-- endinject -->
    <link rel="stylesheet" href="<?php echo site_url('admin/'); ?>css/style.css">
    <link rel="stylesheet" href="<?php echo site_url('admin/'); ?>css/footer.css">
    <!-- endinject -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="<?php echo site_url('admin/'); ?>images/favicon.png" />
    <!-- Essa section redenderizáos estilos de cada view para ester esse layout-->
    <?= $this->renderSection('estilos') ?>
    <style>
        html,
        body {
            height: 100%;
        }

        .container-scroller {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .auth-wrapper {
            flex: 1 0 auto;
            display: flex;
            min-height: 0;
        }

        .auth-wrapper>* {
            flex: 1 0 auto;
        }
    </style>

</head>

<body>
    <div class="container-scroller">
        <div class="auth-wrapper">
            <!-- Essa section redenderizáos estilos de cada view para ester esse layout-->
            <?= $this->renderSection('conteudo') ?>
        </div>

        <?= view('footer') ?>

        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src=" <?php echo site_url('admin/'); ?>vendors/base/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- inject:js -->
    <script src="<?php echo site_url('admin/'); ?>js/off-canvas.js"></script>
    <script src="<?php echo site_url('admin/'); ?>js/hoverable-collapse.js"></script>
    <script src="<?php echo site_url('admin/'); ?>js/template.js"></script>
    <!-- endinject -->
    <?= $this->renderSection('scripts') ?>
</body>

</html>