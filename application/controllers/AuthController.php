<?php

/**
 * Kontroler obsługujący autoryzacje dostepu do strony
 */
class AuthController extends Zend_Controller_Action {

    /**
     * Zmienna do widoków
     * @var string 
     */
    private $viewHelper;

    public function init() {
        $this->viewHelper = new Zend_View_Helper_Action();
    }

    public function indexAction() {
        
    }

    public function logonAction() {
        $this->_helper->layout()->disableLayout();

        $authorization = Zend_Auth::getInstance();
        if ($authorization->hasIdentity()) {
            return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_OK);
        }

        try {
            $form = $this->_helper->Function->filterInputs($this->getAllParams());
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }

        $authorization->setStorage(new Zend_Auth_Storage_Session('Zend_Auth'));

        $login = $form['mail'];
        $password = $form['password'];

        $myAdapter = new My_Auth_Adapter();

        $myAdapter->setIdentity($login);
        $myAdapter->setCredential($password);
        $auth = Zend_Auth::getInstance();
        $result = $auth->authenticate($myAdapter);
        if ($result->isValid()) {
            $storage = $auth->getStorage();
            $storage->write($myAdapter->getResultRowObject());
            return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_OK);
        } else {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), new Exception('Niepoprawne dane logowania'));
        }
    }

    /**
     * Generuje losowy ciag znaków
     * @param  integer $len Długość
     * @return string Wygenerowany ciąg znaków
     */
    private function generateRandomString($len) {
        $rstr = '';
        for ($i = 0; $i < $len; $i++) {
            $p = rand(0, 2);
            switch ($p) {
                case(0): $rstr .= chr(rand(ord('0'), ord('1')));
                    break;
                case(1): $rstr .= chr(rand(ord('A'), ord('Z')));
                    break;
                case(2): $rstr .= chr(rand(ord('a'), ord('z')));
                    break;
            }
        } return $rstr;
    }

    /**
     * Reset hasla usera
     * @return type
     * @throws Exception
     */
    public function passwordRecoveryAction() {
        
    }

    public function loginAction() {
        $this->_helper->layout()->disableLayout();
    }

    /**
     * Wylogowanie z systemu
     */
    public function logoutAction() {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        $this->redirect('');
    }

    /**
     * Sprawdza czy istnieje taki adres e-mail
     */
    public function checkExistMailAction() {
        
    }

    /**
     * Aktualizacja daty logowania usera
     */
    private function updateLastUserLogonDate() {
        
    }

    /**
     * Przedluzenie sesji uzytkownika
     * @return type
     */
    public function extensionLogonSessionAction() {
        
    }

}

require_once 'Zend/Auth/Adapter/Interface.php';

class My_Auth_Adapter implements Zend_Auth_Adapter_Interface {

    protected $_identity = null;
    protected $_credential = null;
    protected $_resultRow = array();

    public function __construct() {
        
    }

    public function authenticate() {
        $result = 0;
        $error = false;

        $api = new Application_Model_Api();

        try {
            $response = $api->login(array(
                'mail' => $this->_identity,
                'password' => $this->_credential
            ));
        } catch (Exception $exc) {
            $error = true;
        }

        if ($response->getStatus() !== 200) {
            $error = true;
        }

        if (!$error) {
            $result = 1;
            $msg = json_decode($response->getBody());
            $this->_resultRow = array(
                'login' => $this->_identity,
                'authToken' => $msg->authToken,
                'name' => $msg->name
            );
        }
        $authResult = new Zend_Auth_Result($result, $this->_identity);
        return $authResult;
    }

    public function setIdentity($value) {
        $this->_identity = $value;
        return $this;
    }

    public function setCredential($credential) {
        $this->_credential = $credential;
        return $this;
    }

    public function getResultRowObject() {

        $returnObject = new stdClass();

        foreach ($this->_resultRow as $resultColumn => $resultValue) {
            $returnObject->{$resultColumn} = $resultValue;
        }

        return $returnObject;
    }

}
