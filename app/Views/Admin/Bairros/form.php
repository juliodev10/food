<div class="form-row">
    <?php if (!$bairro->id): ?>
        <div class="form-group col-md-3">
            <label for="cep-input">CEP</label>
            <input type="text" class="cep form-control" name="cep" id="cep-input"
                value="<?php echo old('cep', esc($bairro->cep)); ?>">
            <div id="cep"></div>
        </div>
    <?php endif; ?>

    <div class="form-group col-md-3">
        <label for="nome">Nome</label>
        <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome"
            value="<?php echo old('nome', esc($bairro->nome)) ?>">
    </div>
    <div class="form-group col-md-3">
        <label for="cidade">Cidade</label>
        <input type="text" class="form-control" name="cidade" id="cidade" placeholder="Cidade"
            value="<?php echo old('cidade', esc($bairro->cidade)) ?>" readonly="">
    </div>
    <?php if (!$bairro->id): ?>
        <div class="form-group col-md-3">
            <label for="estado">Estado</label>
            <input type="text" class="uf form-control" name="estado" id="estado" placeholder="Estado" readonly="">
        </div>
    <?php endif; ?>
</div>

<div class="form-row">
    <div class="form-group col-md-3">
        <label for="valor_entrega">Valor de entrega</label>
        <input type="text" class="money form-control" name="valor_entrega" id="valor_entrega"
            placeholder="Valor de entrega" value="<?php echo old('valor_entrega', esc($bairro->valor_entrega)) ?>">
    </div>

    <div class="form-group col-md-3">
        <label for="ativo">Ativo</label>
        <select class="form-control" name="ativo" id="ativo">
            <?php if ($bairro->id): ?>
                <option value="1" <?= ($bairro->ativo ? 'selected' : ''); ?><?= set_select('ativo', '1') ?>>Sim</option>
                <option value="0" <?= (!$bairro->ativo ? 'selected' : ''); ?><?= set_select('ativo', '0') ?>>Não</option>
            <?php else: ?>
                <option value="1" <?= set_select('ativo', '1') ?>>Sim</option>
                <option value="0" <?= set_select('ativo', '0') ?>>Não</option>
            <?php endif; ?>
        </select>
    </div>
</div>

<div class="form-check form-check-flat form-check-primary mr-2">
    <label class="form-check-label">
        <input type="checkbox" class="form-check-input">
        Lembrar-me
    </label>
</div>