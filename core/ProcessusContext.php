<?php

namespace Processus
{
    class ProcessusContext implements \Processus\Interfaces\InterfaceApplicationContext
    {
        /**
         * @var \Processus\Lib\Profiler\ProcessusProfiler
         */
        private $_profiler;

        /**
         * @var \Processus\ProcessusRegistry
         */
        private $_registry;

        /**
         * @var \Processus\Lib\Facebook\FacebookClient
         */
        private $_facebookClient;

        /**
         * @var UserBo
         */
        private $_userBo;

        /**
         * @var \Processus\Lib\Db\Memcached
         */
        private $_memcached;

        /**
         * @var MySQL
         */
        private $_mysql;

        /**
         * @var \Processus\ProcessusContext
         */
        private static $_instance;

        /**
         * @static
         * @return ProcessusContext
         */
        public static function getInstance()
        {
            if(! self::$_instance)
            {
                self::$_instance = new ProcessusContext();
            }

            return self::$_instance;
        }

        /**
         * @return Lib\Db\Memcached
         */
        public function getDefaultCache ()
        {
            if (! $this->_memcached) {
                
                $config = $this->getRegistry()
                    ->getProcessusConfig()
                    ->getCouchbaseConfig()
                    ->getCouchbasePortByDatabucketKey("default");
                
                $this->_memcached = \Processus\Lib\Server\ServerFactory::memcachedFactory($config['host'], $config['port']);
            }
            
            return $this->_memcached;
        
        }

        /**
         * @return MySQL
         */
        public function getMasterMySql ()
        {
            if (! $this->_mysql) {
                $this->_mysql = \Processus\Lib\Db\MySQL::getInstance();
            }
            
            return $this->_mysql;
        
        }

        /**
         * @return ProcessusRegistry
         */
        public function getRegistry ()
        {
            if (! $this->_registry) {
                $this->_registry = new ProcessusRegistry();
                $this->_registry->init();
            }
            return $this->_registry;
        }

        /**
         * @return Lib\Facebook\FacebookClient
         */
        public function getFacebookClient ()
        {
            if (! $this->_facebookClient) {
                $this->_facebookClient = new \Processus\Lib\Facebook\FacebookClient();
            }
            return $this->_facebookClient;
        }

        // #########################################################
        

        /**
         * @return \Processus\Lib\Bo\UserBo
         */
        public function getUserBo ()
        {
            if (! $this->_userBo) {
                $this->_userBo = new \Processus\Lib\Bo\UserBo();
            }
            return $this->_userBo;
        }

        // #########################################################

        /**
         * @return Profiler
         */
        public function getProfiler ()
        {
            if (! $this->_profiler) {
                $this->_profiler = new \Processus\Lib\Profiler\ProcessusProfiler();
            }
            
            return $this->_profiler;
        }
    }
}

?>