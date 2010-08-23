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
class stShoppingCartItemDoctrine extends stShoppingCartItem implements stShoppingCartItemObject
{
  protected $object;
  
  public function __toString()
  {
    return $this->getObject() ? $this->getObject()->__toString() : '';
  }
 
  public function getObject()
  {
    if ($this->object === null) {
      $this->object = Doctrine::getTable($this->getClass())->find($this->getId());
    }
    
    return $this->object;
  }
}