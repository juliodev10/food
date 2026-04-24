<div class="form-row">
    <div class="form-group col-md-5">
        <label for="nome">Nome</label>
        <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome"
            value="<?php echo old('nome', esc($entregador->nome)) ?>">
    </div>
    <div class="form-group col-md-3">
        <label for="cnh">CNH</label>
        <input type="text" class="form-control cnh" name="cnh" id="cnh" placeholder="CNH"
            value="<?php echo old('cnh', esc($entregador->cnh)) ?>">
    </div>
    <div class="form-group col-md-4">
        <label for="telefone">Telefone</label>
        <input type="text" class="form-control sp_celphones" name="telefone" id="telefone" placeholder="Telefone"
            value="<?php echo old('telefone', esc($entregador->telefone)) ?>">
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-4">
        <label for="email">Email</label>
        <input type="text" class="form-control" name="email" id="email" placeholder="Email"
            value="<?php echo old('email', esc($entregador->email)) ?>">
    </div>
    <div class="form-group col-md-4">
        <label for="veiculo">Veículo</label>
        <input type="text" class="form-control" name="veiculo" id="veiculo" placeholder="Veículo"
            value="<?php echo old('veiculo', esc($entregador->veiculo)) ?>">
    </div>
    <div class="form-group col-md-4">
        <label for="placa">Placa</label>
        <input type="text" class="form-control placa" name="placa" id="placa" placeholder="Placa"
            value="<?php echo old('placa', esc($entregador->placa)) ?>">
    </div>
    <div class="form-group col-md-12">
        <label for="endereco">Endereço</label>
        <input type="text" class="form-control endereco" name="endereco" id="endereco" placeholder="Endereço"
            value="<?php echo old('endereco', esc($entregador->endereco)) ?>">
    </div>
</div>

<div class="form-row">
    <div class="form-group col-md-3">
        <label for="ativo">Ativo</label>
        <select class="form-control" name="ativo" id="ativo">
            <?php if ($entregador->id): ?>
                <option value="1" <?= ($entregador->ativo ? 'selected' : ''); ?><?= set_select('ativo', '1') ?>>Sim</option>
                <option value="0" <?= (!$entregador->ativo ? 'selected' : ''); ?><?= set_select('ativo', '0') ?>>Não</option>
            <?php else: ?>
                <option value="1" <?= set_select('ativo', '1') ?>>Sim</option>
                <option value="0" <?= set_select('ativo', '0') ?>>Não</option>
            <?php endif; ?>
        </select>
    </div>
</div>

<button type="submit" class="btn btn-primary btn-sm btn-icon-text mr-2">
    <i class="mdi mdi-content-save btn-icon-prepend"></i>
    Salvar
</button>