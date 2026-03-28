<h5><?php echo esc($usuario->nome) ?>, agora falta muito pouco!</h5>
<p>Para ativar sua conta e aproveitar ás delícias que a Food Delivery tem a oferecer, clique no link abaixo:</p>
<p><a href="<?= base_url('registrar/ativar/' . esc($usuario->token)) ?>">Ativar conta</a></p>