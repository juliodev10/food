<div class="form-row">
    <?php if ($categoria->id): ?>
        <input type="hidden" name="id" value="<?= (int) $categoria->id; ?>">
    <?php endif; ?>

    <div class="form-group col-md-4">
        <label for="nome">Nome</label>
        <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome"
            value="<?php echo old('nome', esc($categoria->nome)) ?>">
    </div>

    <div class="form-group col-md-3">
        <label for="email">Ativo</label>
        <select class="form-control" name="ativo" id="ativo">
            <?php if ($categoria->id): ?>
                <option value="1" <?= ($categoria->ativo ? 'selected' : ''); ?><?= set_select('ativo', '1') ?>>Sim</option>
                <option value="0" <?= (!$categoria->ativo ? 'selected' : ''); ?><?= set_select('ativo', '0') ?>>Não</option>
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