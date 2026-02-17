<div class="form-row">
    <div class="4">
        <label for="nome">Nome</label>
        <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome"
            value="<?php echo old('nome', esc($extra->nome)) ?>">
    </div>
    <div class="2">
        <label for="preco">Preço de venda</label>
        <input type="text" class="money form-control" name="preco" id="preco" placeholder="Preço de venda"
            value="<?php echo old('preco', esc($extra->preco)) ?>">
    </div>
    <div class="form-group col-md-6">
        <label for="descricao">Descrição</label>
        <textarea name="descricao" id="descricao" class="form-control"
            rows="2"><?php echo old('descricao', esc($extra->descricao)) ?></textarea>
    </div>

    <div class="form-group col-md-3">
        <label for="email">Ativo</label>
        <select class="form-control" name="ativo" id="ativo">
            <?php if ($extra->id): ?>
                <option value="1" <?= ($extra->ativo ? 'selected' : ''); ?><?= set_select('ativo', '1') ?>>Sim</option>
                <option value="0" <?= (!$extra->ativo ? 'selected' : ''); ?><?= set_select('ativo', '0') ?>>Não</option>
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