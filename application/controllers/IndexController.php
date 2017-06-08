<?php

class IndexController extends Zend_Controller_Action {

    private $viewHelper;

    public function init() {
        $this->viewHelper = new Zend_View_Helper_Action();
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->redirect('/auth/login');
        }
        $this->view->page = 'Strona główna';
    }

    public function indexAction() {
        $client = new \GuzzleHttp\Client();
        $res = $client->request('GET', 'http://electroniccvapi.local/app_dev.php/api/test');
        var_dump($res->getStatusCode());
        var_dump($res->getHeaderLine('content-type'));
        $a = json_decode($res->getBody(), true);
        var_dump($a);
    }

}
