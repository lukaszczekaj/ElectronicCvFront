<?php

class AdminController extends Zend_Controller_Action {

    private $viewHelper;

    public function init() {
        $this->viewHelper = new Zend_View_Helper_Action();
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->redirect('/auth/login');
        }
        if (Application_Model_AppRole::getUserRole() != 'admin') {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), new Exception('Brak uprawnień'));
        }
        $this->view->page = 'Panel admina';
    }

    public function indexAction() {
        var_dump(Zend_Auth::getInstance()->getIdentity());

        var_dump(Application_Model_AppRole::getUserRole());
    }

    public function usersAction() {
        $this->view->menuPage = 'users';
        $this->view->page = 'Użytkownicy';
        $this->view->subPage = 'Lista użytkowników';

        $api = new Application_Model_Api();
        try {
            $response = $api->get('/admin-list-users/');
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }
        $users = json_decode($response->getBody(), true);

        try {
            $appRolesResponse = $api->get('/list-app-roles/');
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }
        $appRoles = json_decode($appRolesResponse->getBody(), true);

        foreach ($users as $key => $user) {
            $users[$key]['name'] = (isset($user['firstname']) && isset($user['lastname'])) ? sprintf('%s %s', $user['firstname'], $user['lastname']) : '';

            try {
                $role = $this->getElementFromArrayByKeyName($appRoles, 'id', $user['approleid']);
                $roleName = $role['alias'];
            } catch (Exception $exc) {
                $roleName = '';
            }
            $users[$key]['rolename'] = $roleName;

            $users[$key]['dateregister'] = '';
            if (isset($user['dateregister']) && ($user['dateregister'])) {
                $dateRegister = new Zend_Date($user['dateregister']);
                $users[$key]['dateregister'] = $dateRegister->toString('dd-MM-yyyy HH:mm:ss');
            }

            $users[$key]['datelastlogon'] = '';
            if (isset($user['datelastlogon']) && ($user['datelastlogon'])) {
                $dateRegister = new Zend_Date($user['datelastlogon']);
                $users[$key]['datelastlogon'] = $dateRegister->toString('dd-MM-yyyy HH:mm:ss');
            }
        }
        $this->view->users = $users;
    }

    public function usersAddAction() {
        $this->view->menuPage = 'users';
        $this->view->page = 'Użytkownicy';
        $this->view->subPage = 'Dodaj użytkownika';
    }

    private function getElementFromArrayByKeyName($array, $keyName, $keyValue) {
        if (!is_array($array)) {
            throw new Exception('To nie tablica');
        }
        foreach ($array as $value) {
            if ($value[$keyName] == $keyValue) {
                return $value;
            }
        }
        throw new Exception('Brak takiego rekordu');
    }

    public function userActivateAction() {
        try {
            $form = $this->_helper->Function->filterInputs($this->getAllParams());
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }
        $api = new Application_Model_Api();
        $data = array(
            'action' => 'user-activate',
            'id' => $form['id']
        );
        try {
            $response = $api->add('/admin-action/', $data);
        } catch (Exception $exc) {
            return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_ERROR, $exc->getMessage());
        }
        return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_OK, $response->getBody());
    }

    public function userDeactivateAction() {
        try {
            $form = $this->_helper->Function->filterInputs($this->getAllParams());
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }
        $api = new Application_Model_Api();
        $data = array(
            'action' => 'user-deactivate',
            'id' => $form['id']
        );
        try {
            $response = $api->add('/admin-action/', $data);
        } catch (Exception $exc) {
            return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_ERROR, $exc->getMessage());
        }
        return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_OK, $response->getBody());
    }

}
