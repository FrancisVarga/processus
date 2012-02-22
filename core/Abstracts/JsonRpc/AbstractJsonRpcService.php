<?php

/**
 * @author fightbulc
 *
 *
 */
namespace Processus\Abstracts\JsonRpc
{
    abstract class AbstractJsonRpcService extends \Processus\Abstracts\AbstractClass
    {
        /**
         * @param string $method
         * @param        $request
         * @param        $duration
         * @param        $metaData
         *
         * @return bool|\Zend\Db\Statement\Pdo
         */
        protected function _logJsonRpc(\string $method, $request, $duration, $metaData)
        {
            $mysql = $this->getProcessusContext()->getMasterMySql();

            $insertData = array(
                "method"    => $method,
                "request"   => json_encode($request),
                "meta_data" => json_encode($metaData),
                "duration"  => $duration,
                "created"   => time(),
            );

            return $mysql->insert($this->_getLogTransactionTable(), $insertData);
        }

        /**
         * @return string
         */
        protected function _getLogTransactionTable()
        {
            return "log_json_rpc";
        }
    }
}

?>