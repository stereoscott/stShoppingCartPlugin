<?php

class stShoppingCartGeneratePromoCodesTask extends ifiSiteTask
{
  protected function configure()
  {
    // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('class-names', sfCommandArgument::OPTIONAL, 'A comma separated list of classes to generate. Used to specify the classes to generate if the user doesn\'t want to generate all.'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('num', null, sfCommandOption::PARAMETER_REQUIRED, 'Number of codes to generate', 10),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The database connection', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'shopping-cart';
    $this->name             = 'generate-promo-codes';
    $this->briefDescription = 'Generate a collection of random promo codes';
    $this->detailedDescription = <<<EOF
This task generates promo codes.
Call it with:

  [php symfony ifi:generate-promo-codes|INFO]
EOF;
  }
  
  protected function execute($arguments = array(), $options = array())
  {
    $loggingStateBefore = sfConfig::set('sf_logging_enabled',false);
    sfConfig::set('sf_logging_enabled', false);
   
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'] ? $options['connection'] : null)->getConnection();
    /*
    // So we can play with app.yml settings from the application
    $context = sfContext::createInstance($this->configuration);
    
    sfConfig::set('sf_logging_enabled', $loggingStateBefore);
    
    error_reporting(E_ALL | E_STRICT);
    */
    
    $sql = "INSERT INTO promo_code (name, code, percent_discount, num_uses, use_count) VALUES ('SF Fire Credit Union', '%s', 100, 1, 0);";
    for ($i = 0; $i < $options['num']; $i++) {
      $code = $this->createCode();      
      $stmt = $connection->prepare(sprintf($sql, $code));
      $stmt->execute();
      
      echo $code."\n";      
    }
  }
  
  protected function createCode() 
  { 
    $chars = "ABCDEFGHIJKLMNPQRSTUVWXYZ123456789"; 
    $count = 34;
    $i = 0; 
    $pass = ''; 

    while ($i <= 10) { 
        $num = rand(0,34);
        $tmp = substr($chars, $num, 1); 
        $pass .= $tmp;  // ((($i==0) || $i%4) ? '' : '-') . 
        $i++; 
    } 

    return $pass;
  }
  
}
