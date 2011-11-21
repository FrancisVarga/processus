<?php

/** 
 * @author fightbulc
 * 
 * 
 */
namespace Processus\Abstracts
{
	abstract class AbstractClass
    {
        /**
         * @return \Processus\ProcessusContext
         */
        protected function getApplication()
        {
            return \Processus\ProcessusContext::getInstance();
        }
        
        /**
         * @return mixed | array | stdClass
         */
        protected function config()
        {
            return $this->getApplication()->getRegistry()->getConfig($this);
        }
    }
}

?>