<?php

/**
 * Description of ProfileController
 *
 * @author lukasz
 */
class ProfileController extends Zend_Controller_Action {

    private $viewHelper;

    public function init() {
        $this->viewHelper = new Zend_View_Helper_Action();
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->redirect('/auth/login');
        }
        $this->view->page = 'Profil';
    }

    private function feedView($view, $response) {
        if (!$response) {
            return $view;
        }
        $data = json_decode($response->getBody(), true);
        if (!is_array($data)) {
            return $view;
        }
        foreach ($data as $key => $value) {
            $view->$key = $value;
        }
        return $view;
    }

    public function indexAction() {
        $api = new Application_Model_Api();
        try {
            $response = $api->fetchUserData();
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }
        $this->view = $this->feedView($this->view, $response);
    }

    public function saveAction() {
        try {
            $form = $this->_helper->Function->filterInputs($this->getAllParams());
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }
        $api = new Application_Model_Api();
        try {
            $response = $api->updateProfile($form);
        } catch (Exception $exc) {
            return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_ERROR, $exc->getMessage());
        }
//        return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_OK, 'Zmiany zostały zapisane');
        return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_OK, $response->getBody());
    }

    public function changePasswordAction() {
        try {
            $form = $this->_helper->Function->filterInputs($this->getAllParams());
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }
        $api = new Application_Model_Api();
        try {
            $response = $api->changePassword($form);
        } catch (Exception $exc) {
            return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_ERROR, $exc->getMessage());
        }
        return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_OK, $response->getBody());
    }

}
