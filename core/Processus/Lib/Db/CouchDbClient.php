<?php
/**
 * Created by JetBrains PhpStorm.
 * User: thelittlenerd87
 * Date: 7/25/12
 * Time: 11:31 AM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Lib\Db;
class CouchDbClient implements \Processus\Interfaces\InterfaceDatabase
{
    /**
     * @var \Processus\Lib\Seat\Seat
     */
    private $_client;

    public function __construct($url = "couchdb-server", $user = NULL, $pass = NULL)
    {
        $this->_client = new \Processus\Lib\Seat\Seat($url, $user, $pass);
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function fetch($key = "foobar")
    {
        return $this->_client->get($key);
    }

    /**
     * @throws \Exception
     */
    public function fetchOne($key = NULL)
    {
        return $this->_client->get($key);
    }

    /**
     * @throws \Exception
     */
    public function fetchAll()
    {
        throw new \Exception("Not implemented");
    }

    /**
     * @param string $key
     * @param array  $value
     * @param int    $expiredTime
     *
     * @return int
     */
    public function insert($key = "foobar", $value = array(), $expiredTime = 1)
    {
        $value["_id"] = $key;
        return $this->_client->put($value);
    }

    /**
     * @param array $keys
     *
     * @return mixed
     */
    public function getMultipleByKey(array $keys)
    {
        $stupidPHP = NULL;
        return $this->_client->getMulti($keys, $stupidPHP, \Memcached::GET_PRESERVE_ORDER);
    }

    /**
     * @throws \Exception
     */
    public function update()
    {
        throw new \Exception("Not implemented");
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function delete($key)
    {
        return $this->_client->delete($key);
    }
}