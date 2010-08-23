<?php

class shopping_cartComponents extends sfComponents
{
  public function executeCart()
  {
    $this->shoppingCart = $this->getUser()->getShoppingCart();
  }
  
  public function executeCart_items()
  {
    $this->shoppingCart = $this->getUser()->getShoppingCart();
  }
}