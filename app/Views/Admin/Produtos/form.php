<div class="form-row">
    <div class="form-group col-md-4">
        <label for="nome">Nome</label>
        <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome"
            value="<?php echo old('nome', esc($produto->nome)) ?>">
    </div>
    <div class="form-group col-md-6">
        <label for="ingredientes">Ingredientes</label>
        <textarea name="ingredientes" id="ingredientes" class="form-control"
            rows="3"><?php echo old('ingredientes', esc($produto->ingredientes)) ?></textarea>
    </div>
    <div class="form-group col-md-4">
        <label for="categoria_id">Categoria</label>
        <select name="categoria_id" id="categoria_id" class="form-control">
            <option value="">Escolha a categoria...</option>
            <?php foreach ($categorias as $categoria): ?>
                <?php if ($produto->id): ?>
                    <option value="<?php echo $categoria->id ?>" <?php echo ($categoria->id == $produto->categoria_id ? 'selected' : ''); ?>>
                        <?= esc($categoria->nome); ?>
                    </option>
                <?php else: ?>
                    <option value="<?php echo $categoria->id; ?>"><?= esc($categoria->nome); ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>
    <?php if (usuario_logado()->is_admin && usuario_logado()->id != $usuario->id): ?>
        <div class="form-group col-md-3">
            <label for="ativo">Ativo</label>
            <select class="form-control" name="ativo" id="ativo">
                <?php $ativoSelecionado = old('ativo', $produto->id ? $produto->ativo : '1'); ?>
                <option value="1" <?= set_select('ativo', '1', (string) $ativoSelecionado === '1') ?>>Sim</option>
                <option value="0" <?= set_select('ativo', '0', (string) $ativoSelecionado === '0') ?>>Não</option>
            </select>
        </div>
    <?php endif; ?>

    <div class="form-check form-check-flat form-check-primary mr-2">
        <label class="form-check-label">
            <input type="checkbox" class="form-check-input">
            Lembrar-me
        </label>
    </div>
</div>