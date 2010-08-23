<h2>Shopping Cart</h2>
Items in Cart: <?php echo $shoppingCart->getNbItems() ?> / Total: $<?php echo number_format($shoppingCart->getTotal(), 2) ?>