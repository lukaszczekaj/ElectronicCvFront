<?php

/**
 * Description of RegisterController
 *
 * @author lukasz
 */
class RegisterController extends Zend_Controller_Action {

    private $viewHelper;

    const LENGTH_USER_PASSWORD = 8;

    public function init() {
        $this->viewHelper = new Zend_View_Helper_Action();
        $this->_helper->layout()->disableLayout();
    }

    public function indexAction() {
        
    }

    public function registerUserAction() {
        $form = array(
            'mail' => $this->getParam('mail'),
            'firstName' => $this->getParam('firstName'),
            'lastName' => $this->getParam('lastName'),
            'password' => $this->getParam('password'),
            'retype-password' => $this->getParam('retype-password')
        );
        $api = new Application_Model_Api();
        try {
            $response = $api->registerUser($form);
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }
        $this->view->msg = $response->getBody();
    }

}
