<?php

/**
 * Description of MyException
 *
 * @author lukasz
 */
class Application_Model_Api {

    private $url = 'http://electroniccvapi.local/app_dev.php';
    private $client;
    private $data = array();
    private $authToken = null;

    public function __construct() {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $authData = Zend_Auth::getInstance()->getIdentity();
            $this->data = array_merge($this->data, array('authToken' => $authData->authToken));
        }
        $this->client = new Zend_Rest_Client($this->url);
    }

    public function registerUser($data = array()) {
        $response = $this->client->restPost('/register-user/', $data);
        return $response;
        if ($response->getStatus() !== 200) {
            throw new Exception($response->getBody());
        }
        return $response;
    }

    public function login($data = array()) {
        $response = $this->client->restPost('/login/', $data);
        return $response;
        if ($response->getStatus() !== 200) {
            throw new Exception($response->getBody());
        }
        return $response;
    }

    public function updateProfile($data = array()) {
        $this->data = array_merge($this->data, $data);
        $response = $this->client->restPost('/update-profile/', $this->data);
        if ($response->getStatus() !== 200) {
            throw new Exception($response->getBody());
        }
        return $response;
    }

}
