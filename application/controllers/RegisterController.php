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
        try {
            $form = $this->_helper->Function->filterInputs($this->getAllParams());
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }
        $api = new Application_Model_Api();
        try {
            $response = $api->registerUser($form);
        } catch (Exception $exc) {
            return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_ERROR, $exc->getMessage());
        }
        return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_OK, $response->getBody());
    }

}
