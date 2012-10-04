<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hippsterkiller
 * Date: 9/13/12
 * Time: 10:12 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Lib\Console;
abstract class AbstractConsole extends \Processus\Abstracts\AbstractClass
{

    /**
     * @var array
     */
    private $_payload;

    /**
     * @param array $payload
     */
    public function setPayload($payload)
    {
        $this->_payload = $payload;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return $this->_payload;
    }

    public function __construct($payLoad, $autoStart = TRUE)
    {

        $this->setPayload($payLoad);

        if ($autoStart) {
            $this->runCmd();
        }
    }

    protected function _getConsoleArgs()
    {

    }

    protected function _getConsoleArgByKey($key)
    {

    }

    protected function _getConsoleOptions()
    {

    }

    protected function _getConsoleOptionsByKey($key)
    {

    }

    abstract protected function runCmd();

}
