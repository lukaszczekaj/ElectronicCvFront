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

}
