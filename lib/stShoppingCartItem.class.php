<?php
/**
 *
 * Shopping cart item class.
 *
 * This class represents a shopping cart item.
 *
 * @package    symfony.runtime.addon
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Scott Meves
 */
class stShoppingCartItem
{
  private
    $quantity         = 0,
    $price            = 0,
    $discount         = 0,
    $weight           = 0,
    $class            = '',
    $id               = 0,
    $parameter_holder = null;

  /**
   * Constructs a new item to store in the shopping cart.
   *
   * @param  object shopping cart object
   * @param  string class of this item
   * @param  integer unique identifier of this item
   * @param  integer quantity
   * @param  float price
   * @param  integer discount percentage
   */
  public function __construct($class, $id)
  {
    $this->class            = $class;
    $this->id               = $id;
    $this->parameter_holder = new sfParameterHolder();
  }

  /**
   * Returns unique identifier for this item.
   *
   * @return integer
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Sets unique identifier for this item.
   *
   * @param integer
   */
  public function setId($id)
  {
    $this->id = $id;
  }

  /**
   * Returns class of this item.
   *
   * @return string
   */
  public function getClass()
  {
    return $this->class;
  }

  /**
   * Sets class of this item.
   *
   * @param string
   */
  public function setClass($class)
  {
    $this->class = $class;
  }

  /**
   * Returns price.
   *
   * @return float
   */
  public function getPrice()
  {
    return $this->price;
  }

  /**
   * Sets price.
   *
   * @param float
   */
  public function setPrice($price)
  {
    $this->price = $price;
  }

  /**
   * Returns shopping cart.
   *
   * @return float
   */
  public function getShoppingCart()
  {
    return $this->shopping_cart;
  }

  /**
   * Sets shopping cart for this item.
   *
   * @param float
   */
  public function setShoppingCart($cart)
  {
    $this->shopping_cart = $cart;
  }

  /**
   * Returns weight.
   *
   * @return float
   */
  public function getWeight()
  {
    return $this->weight;
  }

  /**
   * Sets weight.
   *
   * @param float
   */
  public function setWeight($weight)
  {
    $this->weight = $weight;
  }

  /**
   * Returns discount to apply for this item.
   *
   * @return integer percentage of dicount to apply between 0 and 100
   */
  public function getDiscount()
  {
    return $this->discount;
  }

  /**
   * Sets quantity.
   *
   * @param integer
   */
  public function setDiscount($discount)
  {
    $this->discount = $discount;
  }

  /**
   * Returns quantity.
   *
   * @return integer
   */
  public function getQuantity()
  {
    return $this->quantity;
  }

  /**
   * Updates quantity.
   *
   * If $quantity is 0, this item will be automatically deleted from shopping cart.
   *
   * @param integer
   */
  public function setQuantity($quantity)
  {
    if (!preg_match('~^\d+$~', $quantity))
    {
      $this->quantity = 1;
    }
    else
    {
      $this->quantity = $quantity;
    }
  }

  /**
   * Adds quantity to the actual one.
   *
   * @param  integer
   */
  public function addQuantity($quantity)
  {
    $this->quantity += $quantity;
  }

  public function getParameterHolder()
  {
    return $this->parameter_holder;
  }

  public function getParameter($name, $default = null, $ns = null)
  {
    return $this->parameter_holder->get($name, $default, $ns);
  }

  public function hasParameter($name, $ns = null)
  {
    return $this->parameter_holder->has($name, $ns);
  }

  public function setParameter($name, $value, $ns = null)
  {
    return $this->parameter_holder->set($name, $value, $ns);
  }
  
  public function getObject()
  {
    return $this->getShoppingCart()->getObject($this->getClass(), $this->getId());
  }
  
  public function getPurchaseProduct()
  {
    $purchaseProduct = new PurchaseProduct();
    $purchaseProduct->setProduct($this->getObject());
    $purchaseProduct->product_id = $this->getId();
    $purchaseProduct->quantity = $this->getQuantity();
    $purchaseProduct->base_price = $this->getPrice();
    $purchaseProduct->options_price = 0;
    $purchaseProduct->handling_subtotal = 0;
    $purchaseProduct->item_subtotal = ($this->getPrice() * $this->getQuantity());
    $purchaseProduct->item_total = ($this->getPrice() * $this->getQuantity());
    
    return $purchaseProduct;
  }
}

?>