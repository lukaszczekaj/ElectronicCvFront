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

    private function explodeKey($key, $value) {
        switch ($key) {
            case 'birthdate':
                if (!is_null($value)) {
                    $date = new Zend_Date($value, 'yyy-MM-dd HH:mm:ss');
                    $value = $date->toString('MM/dd/YYYY');
                }
                break;

            default:
                break;
        }
        return $value;
    }

    private function feedView($view, $response) {
        if (!$response) {
            return $view;
        }
        $data = json_decode($response->getBody(), true);
        //     var_dump($data);
        if (!is_array($data)) {
            return $view;
        }
        foreach ($data as $key => $value) {
            $view->$key = $this->explodeKey($key, $value);
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

    public function profilePictureUploadAction() {
        $sourcePath = $_FILES['file']['tmp_name'];
        $fileType = $_FILES['file']['type'];
        $imgData = base64_encode(file_get_contents($sourcePath));
        $src = 'data: ' . $fileType . ';base64,' . $imgData;
        $api = new Application_Model_Api();
        try {
            $response = $api->updateProfile(array('image' => $src));
        } catch (Exception $exc) {
            return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_ERROR, $exc->getMessage());
        }
        return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_OK, $response->getBody());
    }

}
