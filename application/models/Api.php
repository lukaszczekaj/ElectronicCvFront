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
            $this->authToken = $authData->authToken;
            $this->data = array_merge($this->data, array('authToken' => $this->authToken));
        }
        $this->client = new Zend_Rest_Client($this->url);
    }

    public function registerUser($data = array()) {
        $response = $this->client->restPost('/register-user/', $data);
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
        if ($response->getStatus() === 403) {
            $auth = Zend_Auth::getInstance();
            $auth->clearIdentity();
            throw new Exception($response->getBody(), 403);
        }
        if ($response->getStatus() !== 200) {
            throw new Exception($response->getBody());
        }
        return $response;
    }
    
    public function changePassword($data = array()) {
        $this->data = array_merge($this->data, $data);
        $response = $this->client->restPost('/change-pass/', $this->data);
        if ($response->getStatus() === 403) {
            $auth = Zend_Auth::getInstance();
            $auth->clearIdentity();
            throw new Exception($response->getBody(), 403);
        }
        if ($response->getStatus() !== 200) {
            throw new Exception($response->getBody());
        }
        return $response;
    }
    
    public function add($url, $data = array()) {
        $this->data = array_merge($this->data, $data);
        $response = $this->client->restPost($url, $this->data);
        if ($response->getStatus() === 403) {
            $auth = Zend_Auth::getInstance();
            $auth->clearIdentity();
            throw new Exception($response->getBody(), 403);
        }
        if ($response->getStatus() !== 200) {
            throw new Exception($response->getBody());
        }
        return $response;
    }
    
    public function get($url) {
        $response = $this->client->restGet(sprintf('%s%s',$url, $this->authToken));
        if ($response->getStatus() === 403) {
            $auth = Zend_Auth::getInstance();
            $auth->clearIdentity();
            throw new Exception($response->getBody(), 403);
        }
        if ($response->getStatus() !== 200) {
            throw new Exception($response->getBody());
        }
        return $response;
    }
    
    
    public function delete($url, $id) {
        $response = $this->client->restDelete(sprintf('%s%s/%s',$url, $this->authToken, $id));
        if ($response->getStatus() === 403) {
            $auth = Zend_Auth::getInstance();
            $auth->clearIdentity();
            throw new Exception($response->getBody(), 403);
        }
        if ($response->getStatus() !== 200) {
            throw new Exception($response->getBody());
        }
        return $response;
    }
    
    public function getCv($id) {
        $response = $this->client->restGet(sprintf('/get-cv/%s/%s', $this->authToken, $id));
        if ($response->getStatus() === 403) {
            $auth = Zend_Auth::getInstance();
            $auth->clearIdentity();
            throw new Exception($response->getBody(), 403);
        }
        if ($response->getStatus() !== 200) {
            throw new Exception($response->getBody());
        }
        return $response;
    }
    
    public function fetchUserData() {
        $response = $this->client->restGet(sprintf('/user-data/%s', $this->authToken));
        if ($response->getStatus() === 403) {
            $auth = Zend_Auth::getInstance();
            $auth->clearIdentity();
            throw new Exception($response->getBody(), 403);
        }
        if ($response->getStatus() !== 200) {
            throw new Exception($response->getBody());
        }
        return $response;
    }

}
