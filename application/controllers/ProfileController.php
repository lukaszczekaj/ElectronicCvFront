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

    public function indexAction() {
        
    }

}
