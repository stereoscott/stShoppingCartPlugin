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
class stShoppingCartItem
{
  private
    $quantity          = 0,
    $price             = 0, // price today, or trial price
    //$price_after_trial = 0, // only for products with a trial_period
    $trial_price       = 0,
    $trial_period      = null,
    $discount          = 0,
    $weight            = 0,
    $class             = '',
    $id                = 0,
    $parameter_holder  = null;

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
   * Returns trial price.
   *
   * @return float
   */
  public function getTrialPrice()
  {
    return $this->trial_price;
  }

  /**
   * Sets trial price.
   *
   * @param float
   */
  public function setTrialPrice($trial_price)
  {
    $this->trial_price = $trial_price;
  }
  
  public function getPriceDueToday()
  {
    return $this->getTrialPeriod() ? $this->getTrialPrice() : $this->getPrice();
  }
  
  public function getPriceAfterTrial()
  {
    return $this->getTrialPeriod() ? $this->getPrice() : false;
  }
  
  /*
  public function getPriceAfterTrial() {
    return $this->price_after_trial;
  }

  public function setPriceAfterTrial($v) {
    $this->price_after_trial = $v;
  }
  */
  
  public function getTrialPeriod() {
    return $this->trial_period;
  }

  public function setTrialPeriod($v) {
    $this->trial_period = $v;
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
    
    $itemTotal = $this->getPrice() * $this->getQuantity();
    $purchaseProduct->fromArray(array(
      'product_id' => $this->getId(),
      'quantity' => $this->getQuantity(),
      'base_price' => $this->getPrice(),
      'options_price' => 0,
      'handling_subtotal' => 0,
      'item_subtotal' => $itemTotal,
      'item_total' => $itemTotal,
      'trial_price' => $this->getTrialPrice(),
      'trial_period' => $this->getTrialPeriod(),
    ));
    
    if ($term = $this->getParameter('term')) {
      $purchaseProduct->term = $term;
    }

    return $purchaseProduct;
  }
}

?>