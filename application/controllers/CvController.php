<?php

/**
 * Description of CvController
 *
 * @author lukasz
 */
class CvController extends Zend_Controller_Action {

    private $viewHelper;

    public function init() {
        $this->viewHelper = new Zend_View_Helper_Action();
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->redirect('/auth/login');
        }
        $this->view->page = 'Moje CV';
        $this->view->menuPage = 'cv';
    }

    public function indexAction() {
        $this->view->subPage = 'Zapisane CV';
    }

    public function addAction() {
        $this->view->subPage = 'Dodaj CV';
    }

    public function educationAction() {
        $this->view->subPage = 'Wykrztałcenie';
    }

    public function workplaceAction() {
        $this->view->subPage = 'Miejsce pracy';
    }

    public function additionalSkillsAction() {
        $this->view->subPage = 'Dodatkowe umiejętności';
    }

    public function linksAction() {
        $this->view->subPage = 'Linki';
    }
    
    public function addEducationAction() {
        try {
            $form = $this->_helper->Function->filterInputs($this->getAllParams());
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }
        return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_WARN, 'Metoda jeszcze nie wspierana');
    }
    
    public function addWorkplaceAction() {
        try {
            $form = $this->_helper->Function->filterInputs($this->getAllParams());
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }
        return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_WARN, 'Metoda jeszcze nie wspierana');
    }
    
    public function addAdditionalSkillsAction() {
        try {
            $form = $this->_helper->Function->filterInputs($this->getAllParams());
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }
        return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_WARN, 'Metoda jeszcze nie wspierana');
    }

}
