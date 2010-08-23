<h2>Shopping Cart</h2>
Items in Cart: <?php echo $shoppingCart->getNbItems() ?> / Total: $<?php echo number_format($shoppingCart->getTotal(), 2) ?>

<ul>
  <?php foreach ($shoppingCart->getItems() as $item): ?>
  <li><?php echo $item ?> - <?php echo $item->getQuantity() ?> x $<?php echo number_format($item->getPrice(), 2) ?></li>
  <?php endforeach ?>
</ul>