<?php
/**
 * Created by JetBrains PhpStorm.
 * User: francis
 * Date: 11/28/11
 * Time: 4:01 PM
 * To change this template use File | Settings | File Templates.
 */
namespace Processus\Profiler
{
    class ProcessusDebugProfilerVo extends \Processus\Abstracts\Vo\AbstractVO implements \Processus\Lib\Profiler\IProcessusDebugProfilerVo
    {
        /**
         * @return ProcessusDebugProfilerVo
         */
        public function startTimeTrack()
        {
            $this->setValueByKey("startTimeTrack", time());
            return $this;
        }

        /**
         * @return ProcessusDebugProfilerVo
         */
        public function endTimeTrack()
        {
            $this->setValueByKey("endTimeTrack", time());
            return $this;
        }

        /**
         * @param string $method
         *
         * @return \Processus\Lib\Vo\Profiler\ProfilerVo
         */
        public function setMethod(\string $method)
        {
            $this->setValueByKey('method', $method);

            return $this;
        }

        /**
         * @return array|mixed
         */
        public function getMethod()
        {
            return $this->getValueByKey('method');
        }

        /**
         * @param string $line
         *
         * @return ProfilerVo
         */
        public function setLine(\string $line)
        {
            $this->setValueByKey('line', $line);
            return $this;
        }

        /**
         * @return array|mixed
         */
        public function getLine()
        {
            return $this->getValueByKey('line');
        }

        /**
         * @param string $comment
         *
         * @return ProfilerVo
         */
        public function setComment(\string $comment)
        {
            $this->setValueByKey('comment', $comment);
            return $this;
        }

        /**
         * @return array|mixed
         */
        public function getComment()
        {
            return $this->getValueByKey('comment');
        }

        /**
         * @param string $class
         *
         * @return ProfilerVo
         */
        public function setClass(\string $class)
        {
            $this->setValueByKey('class', $class);
            return $this;
        }

        /**
         * @return array|mixed
         */
        public function getClass()
        {
            return $this->getValueByKey('class');
        }
    }
}