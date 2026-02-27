<div class="form-row">
    <div class="form-group col-md-12">
        <label for="nome">Nome</label>
        <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome"
            value="<?php echo old('nome', esc($forma->nome)) ?>">
    </div>

    <div class="form-group col-md-12">
        <label for="email">Ativo</label>
        <select class="form-control" name="ativo" id="ativo">
            <?php if ($forma->id): ?>
                <option value="1" <?= ($forma->ativo ? 'selected' : ''); ?><?= set_select('ativo', '1') ?>>Sim</option>
                <option value="0" <?= (!$forma->ativo ? 'selected' : ''); ?><?= set_select('ativo', '0') ?>>Não</option>
            <?php else: ?>
                <option value="1" <?= set_select('ativo', '1') ?>>Sim</option>
                <option value="0" <?= set_select('ativo', '0') ?>>Não</option>
            <?php endif; ?>
        </select>
    </div>

    <div class="form-check form-check-flat form-check-primary mr-2">
        <label class="form-check-label">
            <input type="checkbox" class="form-check-input">
            Lembrar-me
        </label>
    </div>
</div>