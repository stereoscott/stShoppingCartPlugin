<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class PluginPromoCode extends BasePromoCode
{
  public function incrementUseCount(Doctrine_Connection $conn = null)
  {
    if (!$this->use_count) {
      $this->use_count = 0;
    }
    
    $this->use_count += 1;
    
    return $this->save($conn);
  }
}