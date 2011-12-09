<?php

namespace Processus\Lib\Db
{
    /**
     *
     */
    class MySQL implements \Processus\Interfaces\InterfaceDatabase
    {
        /**
         * @var \Processus\Lib\Db\MySQL
         */
        private static $_instance;

        /**
         * @var \Zend\Db\Adapter\AbstractAdapter
         */
        public $dbh;

        // #########################################################


        /**
         * @static
         * @return MySQL
         */
        public static function getInstance()
        {
            if (self::$_instance instanceof self !== TRUE) {
                self::$_instance = new MySQL();
                self::$_instance->init();
            }

            return self::$_instance;
        }

        // #########################################################


        /**
         * @return void
         */
        public function init()
        {
            $registry = \Processus\ProcessusContext::getInstance()->getRegistry();
            $masters = $registry->getProcessusConfig()
                ->getMysqlConfig()
                ->getValueByKey('masters');

            $this->dbh = \Zend\Db\Db::factory($masters[0]->adapter, $masters[0]->params->toArray());
        }

        // #########################################################        


        /**
         * @param null $sql
         * @param array $args
         *
         * @return \Zend\Db\Statement\Pdo
         */
        private function _prepare($sql = NULL, $args = array())
        {
            $stmt = new \Zend\Db\Statement\Pdo($this->dbh, $sql);
            $stmt->setFetchMode(\Zend\Db\Db::FETCH_OBJ);
            $stmt->execute($args);
            return $stmt;
        }

        // #########################################################


        /**
         * @param null $sql
         * @param array $args
         *
         * @return string
         */
        public function fetchValue($sql = NULL, $args = array())
        {
            return $this->_prepare($sql, $args)->fetchColumn();
        }

        // #########################################################


        /**
         * @param string $sql
         * @param array $args
         *
         * @return \Zend\Db\Statement\Pdo
         */
        public function fetch($sql = NULL, $args = array())
        {
            return $this->_prepare($sql, $args);
        }

        // #########################################################


        /**
         * @param null $sql
         * @param array $args
         *
         * @return mixed
         */
        public function fetchOne($sql = NULL, $args = array())
        {
            return $this->_prepare($sql, $args)->fetchObject();
        }

        // #########################################################


        /**
         * @param null $sql
         * @param array $args
         *
         * @return array
         */
        public function fetchAll($sql = NULL, $args = array())
        {
            return $this->_prepare($sql, $args)->fetchAll();
        }

        // #########################################################


        /**
         * @param null $tableName
         * @param array $values
         *
         * @return mixed
         */
        public function insert($tableName = NULL, $values = array())
        {
            if (!empty($tableName) && !empty($values)) {

                // prepare placeholders and values
                $_set = array();
                $_placeholder = array();
                $_values = array();

                foreach ($values as $key => $val) {
                    $_set[] = $key;

                    $placeholder_key = ':' . $key;
                    $_placeholder[] = $placeholder_key;

                    $_values[$placeholder_key] = $val;
                }

                // build sql
                $sql = 'INSERT INTO ' . $tableName . ' (' . join(',', $_set) . ') VALUES (' . join(',', $_placeholder) . ')';

                // insert
                return $this->_prepare($sql, $_values);
            }

            return;
        }

        // #########################################################


        /**
         * @param null $tableName
         * @param array $values
         * @param array $conditions
         *
         * @return mixed
         */
        public function update($tableName = NULL, $values = array(), $conditions = array())
        {
            if (!is_null($tableName) && !empty($values) && array_key_exists('id', $conditions)) {
                // prepare placeholders and values
                $_set = array();
                $_values = array();

                foreach ($values as $key => $val) {
                    $placeholder_key = ':' . $key;
                    $_set[] = $key . '=' . $placeholder_key;
                    $_values[$placeholder_key] = $val;
                }

                // prepare conditions
                $_cond = array();

                foreach ($conditions as $key => $val) {
                    $placeholder_key = ':_' . $key;
                    $_cond[] = $key . '=' . $placeholder_key;
                    $_values[$placeholder_key] = $val;
                }

                // build sql
                $sql = 'UPDATE ' . $tableName . ' SET ' . join(',', $_set) . ' WHERE ' . join(' AND ', $_cond);

                // update
                return $this->_prepare($sql, $_values);
            }

            return;
        }
    }
}

?>