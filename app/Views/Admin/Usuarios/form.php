<div class="form-row">
    <div class="form-group col-md-4">
        <label for="nome">Nome</label>
        <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome"
            value=" <?php echo esc($usuario->nome) ?>">
    </div>
    <div class="form-group col-md-2">
        <label for="cpf">CPF</label>
        <input type="text" class="form-control cpf" name="cpf" id="cpf" placeholder="CPF"
            value=" <?php echo esc($usuario->cpf) ?>">
    </div>
    <div class="form-group col-md-3">
        <label for="telefone">Telefone</label>
        <input type="text" class="form-control sp_celphones" name="telefone" id="telefone" placeholder="Telefone"
            value="<?php echo esc($usuario->telefone) ?>">
    </div>
    <div class="form-group col-md-3">
        <label for="email">Email</label>
        <input type="text" class="form-control" name="email" id="email" placeholder="Email"
            value="<?php echo esc($usuario->email) ?>">
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-3">
        <label for="password">Senha</label>
        <input type="password" class="form-control" name="password" id="password" placeholder="Senha">
    </div>
    <div class="form-group col-md-3">
        <label for="confirmation_password">Confirmar Senha</label>
        <input type="password" class="form-control" name="confirmation_password" id="confirmation_password"
            placeholder="Senha">
    </div>

    <div class="form-group col-md-3">
        <label for="email">Perfil de acesso</label>
        <select class="form-control" name="is_admin" id="is_admin">
            <?php if ($usuario->id): ?>
                <option value="1" <?= ($usuario->is_admin ? 'selected' : ''); ?>>Administrador</option>
                <option value="0" <?= (!$usuario->is_admin ? 'selected' : ''); ?>>Cliente</option>
            <?php else: ?>
                <option value="1">Administrador</option>
                <option value="0">Cliente</option>
            <?php endif; ?>
        </select>
    </div>
    <div class="form-group col-md-3">
        <label for="email">Ativo</label>
        <select class="form-control" name="ativo" id="ativo">
            <?php if ($usuario->id): ?>
                <option value="1" <?= ($usuario->ativo ? 'selected' : ''); ?>>Sim</option>
                <option value="0" <?= (!$usuario->ativo ? 'selected' : ''); ?>>Não</option>
            <?php else: ?>
                <option value="1">Sim</option>
                <option value="0">Não</option>
            <?php endif; ?>
        </select>
    </div>

    <div class="form-check form-check-flat form-check-primary mr-2">
        <label class="form-check-label">
            <input type="checkbox" class="form-check-input">
            Lembrar-me
        </label>
    </div>
    <button type="submit" class="btn btn-primary btn-sm btn-icon-text mr-2">
        <i class="mdi mdi-content-save btn-icon-prepend"></i>
        Salvar
    </button>
    <a href="<?= site_url("admin/usuarios/show/$usuario->id"); ?>" class="btn btn-light btn-sm btn-icon-text">
        <i class="mdi mdi-arrow-left btn-icon-prepend"></i> Voltar</a>