<?php
/**
 *
 * Shopping cart item class.
 *
 * This class represents a shopping cart item.
 *
 * @package    symfony.runtime.addon
 * @author     Scott Meves
 */
class stShoppingCartItemProduct extends stShoppingCartItemDoctrine
{
  public function __construct($product)
  {
    parent::__construct('Product', $product['id']);
    $this->object = $product; 
  }
}