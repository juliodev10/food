<h3>Obaaa, o seu pedido <?php echo esc($pedido->nome) ?>, saiu para entrega!</h3>
<p>Olá, <strong><?php echo esc($pedido->nome); ?>, so seu pedido <?php echo esc($pedido->nome); ?> </strong> saiu pra entrega!</p>
<p>A forma de pagamento escolhida foi: <strong><?php echo esc($pedido->forma_pagamento); ?></strong></p>
<p>Verificamos aqui que o endereço de entrega é: <strong><?php echo esc($pedido->endereco_entrega); ?></strong></p>
<p>Observações do pedido: <strong><?php echo esc($pedido->observacoes); ?></strong></p>
<hr>
<p>O entregador responsável por levar o seu pedido é: <strong><?php echo esc($pedido->entregador->nome); ?></strong></p>
<hr>
<p>Aproveite pra ver os seus <a href="<?php echo site_url('conta/pedidos') ?>">pedidos</a>!</p>