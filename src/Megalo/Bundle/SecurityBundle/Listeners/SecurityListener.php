<?php

namespace Megalo\Bundle\SecurityBundle\Listeners;

use Doctrine\ORM\Event\LifecycleEventArgs;
/**
 *
 * @author ydevars
 */
class SecurityListener
{


 public function persist(LifecycleEventArgs $args)
    {
       $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();
        
        
    }
    
    
  

}

?>
