<?php

/**
 *
 * Shopping cart class for Symfony 1.2. Based on sfShoppingCartPlugin.
 *
 * This class can manage carts. A cart can contains instances of objects from different classes (items).
 * Each item is a sfShoppingCartItem object.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Scott Meves
 */
class stShoppingCart
{
  protected
    $items   = array(),
    $orderId = null,
    $tax,
    $flatDiscount = null,
    $percentDiscount = null,
    $is_unit_price_ttc = false;

  /**
   * Constructs a shopping cart with the given tax as a parameter.
   * Tax is a percentage.
   *
   * @param  integer
   */
  public function __construct($tax = 0)
  {
    $this->tax = $tax;
  }

  /**
   * Sets if item unit price is with taxes included.
   *
   * @param  boolean
   */
  public function setUnitPriceWithTaxes($boolean)
  {
    $this->is_unit_price_ttc = $boolean;
  }

  /**
   * Returns true if item unit price is with taxes included.
   *
   * @return boolean
   */
  public function getUnitPriceWithTaxes()
  {
    return $this->is_unit_price_ttc;
  }

  /**
   * Returns tax to apply on items.
   *
   * @return string
   */
  public function getTax()
  {
    return $this->tax;
  }

  /**
   * Set tax to apply on items. This tax is applied on the total price.
   * Tax is a percentage.
   *
   * @param  string tax as a percentage between 0 and 100
   */
  public function setTax($tax)
  {
    $this->tax = $tax;
  }

  public function getFlatDiscount() {
    return $this->flatDiscount;
  }

  public function setFlatDiscount($v) {
    $this->flatDiscount = $v;
  }

  public function getPercentDiscount() {
    return $this->percentDiscount;
  }

  public function setPercentDiscount($v) {
    $this->percentDiscount = $v;
  }

  
  /**
   * Returns item (sfShoppingCartItem instance) from the shopping cart or null if not found.
   *
   * @param  string class of item
   * @param  integer unique identifier for this item of this class (primary key for database object for example)
   * @return object
   */
  public function getItem($class, $id)
  {
    $ind = $this->getItemIndice($class, $id);

    return (($ind !== null) ? $this->items[$ind] : null);
  }

  /**
   * Returns indice of the given item in the $this->items array.
   *
   * @param  string class of item
   * @param  integer unique identifier for this item of this class (primary key for database object for example)
   * @return integer
   */
  public function getItemIndice($class, $id)
  {
    $ind = null;

    foreach ($this->items as $key => $item)
    {
      if ($item->getClass() == $class && $item->getId() == $id)
      {
        $ind = $key;
        break;
      }
    }

    return $ind;
  }

  /**
   * Adds an item to the shopping cart.
   *
   * @param  string class of item
   * @param  integer unique identifier for this item of this class (primary key for database object for example)
   * @param  integer quantity
   * @param  float unit price of item
   * @param  integer percentage of discount to apply to this item
   */
  public function addItem($item)
  {
    $existingItem = $this->getItem($item->getClass(), $item->getId());
    if ($existingItem)
    {
      $existingItem->addQuantity($item->getQuantity());
    }
    else
    {
      $this->items[] = $item;
    }
  }

  /**
   * Deletes item from the shopping cart.
   *
   * This is equivalent to call <code>$cart->updateQuantity($class, $id, 0)</code>
   *
   * @param  string class of item
   * @param  integer unique identifier for this item of this class (primary key for database object for example)
   */
  public function deleteItem($class, $id)
  {
    foreach (array_keys($this->items) as $i)
    {
      if ($this->items[$i]->getClass() == $class && $this->items[$i]->getId() == $id)
      {
        unset($this->items[$i]);
      }
    }
  }

  /**
   * Returns order id.
   *
   * @return  mixed
   *
   */
  public function getOrderId()
  {
    return $this->orderId;
  }

  /**
   * Sets order id.
   *
   * @param  mixed
   *
   */
  public function setOrderId($order)
  {
    $this->orderId = $order;
  }

  /**
   * Returns total weight for all items in the shopping cart.
   *
   * @return  float
   */
  public function getTotalWeight()
  {
    $total_weight = 0;

    foreach ($this->getItems() as $item)
    {
      $total_weight += $item->getQuantity() * $item->getWeight();
    }

    return $total_weight;
  }

  public function applyDiscounts($total_ht)
  {
    if (null !== $this->getPercentDiscount()) {
      $total_ht = $total_ht * (1 - $this->getPercentDiscount() / 100);
    }
    
    if (null !== $this->getFlatDiscount()) {
      $total_ht -= $this->getFlatDiscount();
    }
    
    return $total_ht;
  }
  /**
   * Returns total price for all items in the shopping cart.
   *
   * @return  float
   */
  public function getTotal()
  {
    $total_ht = 0;

    foreach ($this->getItems() as $item)
    {
      if ($this->is_unit_price_ttc)
      {
        $total_ht += $item->getQuantity() * $item->getPrice() * (1 - $item->getDiscount() / 100) / (1 + $this->tax / 100);
      }
      else
      {
        $total_ht += $item->getQuantity() * $item->getPrice() * (1 - $item->getDiscount() / 100);
      }
    }
    
    $total_ht = $this->applyDiscounts($total_ht);

    return $total_ht;
  }

  /**
   * Returns total price for all items in the shopping cart with taxes added.
   *
   * This is equivalent to <code>$cart->getTotal() * (1 + $cart->getTax() / 100)</code>
   *
   * @return  float
   */
  public function getTotalWithTaxes()
  {
    $total_ttc = 0;

    foreach ($this->getItems() as $item)
    {
      if ($this->is_unit_price_ttc)
      {
        $total_ttc += $item->getQuantity() * $item->getPrice() * (1 - $item->getDiscount() / 100);
      }
      else
      {
        $total_ttc += $item->getQuantity() * $item->getPrice() * (1 - $item->getDiscount() / 100) * (1 + $this->tax / 100);
      }
    }
    
    $total_ttc = $this->applyDiscounts($total_ttc);

    return $total_ttc;
  }

  /**
   * Returns all items (sfShoppingCart objects) in the shopping cart.
   *
   * @return array
   */
  public function getItems()
  {
    // if we find item with a quantity of 0, we remove it from the shopping cart
    $items = array();
    foreach ($this->items as $item)
    {
      if ($item->getQuantity() != 0)
      {
        $items[] = $item;
      }
    }

    return $items;
  }

  /**
   * Returns the number of items in the shopping cart.
   *
   * @param boolean count the actual number of items instead of the number of items in the array.
   * @return integer
   */
  public function getNbItems($countQuantities = false)
  {
    if ($countQuantities)
    {
      $itemCount = 0;
      foreach ($this->items as $item)
      {
        $itemCount += $item->getQuantity();
      }

      return $itemCount;
    }

    return count($this->getItems());
  }

  /**
   * Is shopping cart empty?
   *
   * @return boolean
   */
  public function isEmpty()
  {
    return ($this->getNbItems() ? false : true);
  }

  /**
   * Removes all items from the shopping cart.
   */
  public function clear()
  {
    $this->items = array();
  }

  /**
   * Returns a particular Doctrine object (item) of the shopping cart.
   * 
   * This method can only be called if all items are Doctrine objects.
   *
   * @param  string class of item
   * @param  integer unique identifier for this item of this class (primary key for database object for example)
   * @return object
   */
  public function getObject($class, $id)
  {
    // We must first make sure that the requested object does exist in the shopping cart
    $ind = $this->getItemIndice($class, $id);
    
    return (($ind !== null) ? Doctrine::getTable($class)->find($id) : null);
  }

  /**
   * Returns an array of all Propel objects (items) in the shopping cart, ordered by class.
   *
   * This method can only be called if all items are Propel objects.
   */
  public function getObjects()
  {
    $object_ids = array();
    foreach ($this->getItems() as $item)
    {
      if (!array_key_exists($item->getClass(), $object_ids))
      {
        $object_ids[$item->getClass()] = array();
      }

      $object_ids[$item->getClass()][] = $item->getId();
    }

    $objects = array();
    foreach ($object_ids as $class => $ids)
    {
      $q = Doctrine::getTable($class)->createQuery('p')->where('p.id in (?)', $ids);      
      $objects = array_merge($objects, $q->fetchArray());
    }

    return $objects;
  }
}

?>