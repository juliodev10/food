<h5>Pedido <?php echo esc($pedido->codigo) ?>, realizado com sucesso!</h5>
<p>Olá <strong><?php echo esc($pedido->usuario->nome); ?></strong>, recebemos seu pedido <strong><?php echo esc($pedido->codigo) ?></strong> e estamos processando-o.</p>
<p>Entre em sua conta para acompanhar o status do seu pedido: <a href="<?php echo site_url('conta'); ?>">Clique aqui</a></p>