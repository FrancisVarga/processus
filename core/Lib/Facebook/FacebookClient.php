<?php

/**
 * @author francis
 *
 *
 */

namespace Processus\Lib\Facebook
{
    class FacebookClient extends \Processus\Abstracts\AbstractClass
    {

        /**
         * @var \Processus\Contrib\Facebook\Facebook
         */
        private $_facebookSdk;

        /**
         * @return Ambigous <\Processus\multitype:, multitype:>
         */
        private $_facebookSdkConf;

        /**
         * @var mixed | array
         */
        private $_userFacebookData;

        /**
         * @var mixed | array
         */
        private $_facebookFriends;

        /**
         * @var \Processus\Lib\Facebook\Api\OpenGraph
         */
        private $_openGraphClient;

        /**
         * @return string
         */
        public function getLoginUrl()
        {
            return $this->getFacebookSdk()->getLoginUrl();
        }

        /**
         * @return mixed
         */
        public function getAppId()
        {
            $fbConfig = $this->getFacebookClientConfig();
            return $fbConfig['appId'];
        }

        /**
         * @return mixed
         */
        protected function getFacebookClientConfig()
        {
            if (!$this->_facebookSdkConf) {
                /**  */
                $this->_facebookSdkConf = $this->getProcessusContext()
                    ->getRegistry()
                    ->getConfig("Facebook");
            }
            return $this->_facebookSdkConf;
        }

        /**
         * @return array|mixed
         */
        public function getUserFacebookData()
        {
            if (!$this->_userFacebookData) {

                try
                {

                    $this->_userFacebookData = $this->getFacebookSdk()->api("/me");

                }
                catch (\Exception $error)
                {
                    $this->getProcessusContext()->getErrorLogger()->log('User API ME FAILED', 100, $this->_userFacebookData);
                    $this->getProcessusContext()->getErrorLogger()->log('Error', 100, $error);

                    throw $error;
                }
            }

            return $this->_userFacebookData;
        }

        /**
         * @return string
         */
        public function isUserAuthorizedOnFacebook()
        {
            return $this->getFacebookSdk()->getAccessToken();
        }

        /**
         * @return string
         */
        public function getUserId()
        {
            return $this->getFacebookSdk()->getUser();
        }

        /**
         * @return mixed
         */
        public function getUserFriends()
        {
            $defaultCache = $this->getProcessusContext()->getDefaultCache();
            $fbNum        = $this->getUserId();
            $memKey       = "getUserFriends_" . $fbNum;

            $facebookFriends = $defaultCache->fetch($memKey);

            if (!$facebookFriends) {
                $rawData         = $this->getFacebookSdk()->api("/me/friends");
                $facebookFriends = $rawData['data'];

                $defaultCache->insert($memKey, $facebookFriends, 60 * 60 * 3);
            }

            return $facebookFriends;
        }

        /**
         * @return \Processus\Contrib\Facebook\Facebook
         */
        protected function getFacebookSdk()
        {
            if (!$this->_facebookSdk) {
                $this->_facebookSdk = new \Processus\Contrib\Facebook\Facebook($this->getFacebookClientConfig()->toArray());
            }

            return $this->_facebookSdk;
        }

        /**
         * @return array
         */
        public function getFriendsIdList()
        {
            $friendsList = $this->getUserFriends();

            $idList = array();

            foreach ($friendsList as $item) {
                $idList[] = $item['id'];
            }

            return $idList;
        }

        /**
         * @param string $facebookUserId
         *
         * @return array|mixed
         */
        public function getUserDataById(string $facebookUserId)
        {
            try {
                $userData = $this->getFacebookSdk()->api("/" . $facebookUserId);
            }
            catch (\Exception $error)
            {
                throw $error;
            }

            return $userData;
        }

        /**
         * @return \Processus\Lib\Facebook\Api\OpenGraph
         */
        public function getOpenGraphClient()
        {
            if ($this->_openGraphClient) {
                $this->_openGraphClient = new \Processus\Lib\Facebook\Api\OpenGraph();
            }

            return $this->_openGraphClient;
        }
    }
}
?>