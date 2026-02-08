<div class="form-row">
    <div class="form-group col-md-4">
        <label for="nome">Nome</label>
        <input type="text" class="form-control" name="nome" id="nome" placeholder="Nome"
            value="<?php echo $usuario->nome ?>">
    </div>
    <div class="form-group col-md-2">
        <label for="cpf">CPF</label>
        <input type="text" class="form-control" name="cpf" id="cpf" placeholder="CPF"
            value="<?php echo $usuario->cpf ?>">
    </div>
    <div class="form-group col-md-3">
        <label for="telefone">Telefone</label>
        <input type="text" class="form-control" name="telefone" id="telefone" placeholder="Telefone"
            value="<?php echo $usuario->telefone ?>">
    </div>
    <div class="form-group col-md-3">
        <label for="email">Email</label>
        <input type="text" class="form-control" name="email" id="email" placeholder="Email"
            value="<?php echo $usuario->email ?>">
    </div>
</div>

<div class="form-group">
    <label for="exampleInputUsername1">Username</label>
    <input type="text" class="form-control" id="exampleInputUsername1" placeholder="Username">
</div>
<div class="form-group">
    <label for="exampleInputEmail1">Email address</label>
    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email">
</div>
<div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
</div>
<div class="form-group">
    <label for="exampleInputConfirmPassword1">Confirm Password</label>
    <input type="password" class="form-control" id="exampleInputConfirmPassword1" placeholder="Password">
</div>
<div class="form-check form-check-flat form-check-primary">
    <label class="form-check-label">
        <input type="checkbox" class="form-check-input">
        Remember me
    </label>
</div>
<button type="submit" class="btn btn-primary mr-2">Submit</button>
<button class="btn btn-light">Cancel</button>