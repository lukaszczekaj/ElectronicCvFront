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
    }

    public function indexAction() {
        
    }

    public function registerUserAction() {
        
    }

}
