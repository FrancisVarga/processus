<?php

namespace Processus\Abstracts\Manager
{

    abstract class AbstractManager extends \Processus\Abstracts\AbstractClass
    {

        /**
         * @var \Processus\Lib\Db\Memcached
         */
        protected $_memcached;

        /**
         * @return string
         */
        protected function getDataBucketKey()
        {
            return 'default';
        }

        // #########################################################


        /**
         * @return \Processus\Lib\Db\Memcached
         */
        protected function getMemcached()
        {
            if (!$this->_memcached) {
                $config = $this->getApplication()
                    ->getRegistry()
                    ->getProcessusConfig()
                    ->getCouchbaseConfig()
                    ->getCouchbasePortByDatabucketKey($this->getDataBucketKey());

                $this->_memcached = \Processus\Lib\Server\ServerFactory::memcachedFactory($config['host'], $config['port']);
            }

            return $this->_memcached;

        }

        // #########################################################


        /**
         * @param \Processus\Interfaces\InterfaceComConfig $com
         *
         * @return mixed
         */
        protected function getDataFromCache(\Processus\Interfaces\InterfaceComConfig $com)
        {
            return $this->getMemcached()->fetch($com->getMemId());
        }

        // #########################################################


        /**
         * @param InterfaceComConfig $com
         *
         * @return mixed|null
         */
        protected function fetch(InterfaceComConfig $com)
        {
            $results = NULL;

            if ($com->getFromCache() === TRUE) {
                $results = $this->getDataFromCache($com);
            }

            if (empty($results)) {
                $results = $this->_fetchFromMysql($com);
                $this->cacheResult($com, $results);
            }

            return $results;
        }

        // #########################################################


        /**
         * @param \Processus\Interfaces\InterfaceComConfig $com
         * @return mixed|null
         */
        protected function fetchOne(\Processus\Interfaces\InterfaceComConfig $com)
        {
            $results = NULL;

            if ($com->getFromCache() === TRUE) {
                $results = $this->getDataFromCache($com);
            }

            if (empty($results)) {
                $results = $this->_fetchOneFromMysql($com);
                $this->cacheResult($com, $results);
            }

            return $results;
        }

        // #########################################################


        /**
         * @param \Processus\Interfaces\InterfaceComConfig $com
         *
         * @return mixed|null
         */
        protected function fetchAll(\Processus\Interfaces\InterfaceComConfig $com)
        {
            $results = NULL;

            if ($com->getFromCache() === TRUE) {
                $results = $this->getDataFromCache($com);
            }

            if (empty($results)) {
                $results = $this->_fetchAllFromMysql($com);
                $this->cacheResult($com, $results);
            }

            return $results;
        }

        // #########################################################


        /**
         * @param \Processus\Interfaces\InterfaceComConfig $com
         * @return \Zend\Db\Statement\Pdo
         */
        protected function insert(\Processus\Interfaces\InterfaceComConfig $com)
        {
            return $com->getConnector()->insert($com->getSqlTableName(), $com->getSqlParams());
        }

        // #########################################################


        /**
         * @param \Processus\Interfaces\InterfaceComConfig $com
         * @return mixed
         */
        protected function update(\Processus\Interfaces\InterfaceComConfig $com)
        {
            return $com->getConnector()->update($com->getSqlTableName(), $com->getSqlParams(), $com->getSqlConditions());
        }

        // #########################################################


        /**
         * @param \Processus\Interfaces\InterfaceComConfig $com
         *
         * @return mixed
         */
        protected function _fetchFromMysql(\Processus\Interfaces\InterfaceComConfig $com)
        {
            return $com->getConnector()->fetch($com->getSqlStmt(), $com->getSqlParams());
        }

        // #########################################################


        /**
         * @param \Processus\Interfaces\InterfaceComConfig $com
         *
         * @return mixed
         */
        protected function _fetchOneFromMysql(\Processus\Interfaces\InterfaceComConfig $com)
        {
            return $com->getConnector()->fetchOne($com->getSqlStmt(), $com->getSqlParams());
        }

        // #########################################################


        /**
         * @param \Processus\Interfaces\InterfaceComConfig $com
         *
         * @return mixed
         */
        protected function _fetchAllFromMysql(\Processus\Interfaces\InterfaceComConfig $com)
        {
            return $com->getConnector()->fetchAll($com->getSqlStmt(), $com->getSqlParams());
        }

        // #########################################################


        /**
         * @param \Processus\Interfaces\InterfaceComConfig $com
         * @param                                          $results
         */
        protected function cacheResult(\Processus\Interfaces\InterfaceComConfig $com, $results)
        {
            $this->getMemcached()->insert($com->getMemId(), $results, $com->getExpiredTime());
        }

    }
}

?>