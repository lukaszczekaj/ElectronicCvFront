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
    
    public function saveAction() {
        try {
            $form = $this->_helper->Function->filterInputs($this->getAllParams());
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }
        return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_WARN, 'Metoda jeszcze nie wspierana');
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
    
    public function pdfAction() {
        $pdf = new CvPdfGenerator();
        $pdf->getPDF();
        
        exit();
    }

}

/**
 * Wygenerowanie PDFa faktury
 */
class CvPdfGenerator {

    private $data;

    public function setData($data) {
        $this->data = $data;
    }

    public function getPDF($fileName = 'file.pdf', $type = 'I') {
        $pdf = new \mPDF('utf-8', 'A4');
        $pdf->WriteHTML($this->getCSS(), 1);
        $pdf->WriteHTML($this->getHTML(), 2);
        return $pdf->Output($fileName, $type);
    }

    private function getCSS() {
        return file_get_contents(APPLICATION_PATH . '/../public/dist/css/main.min.css');
    }

    private function getHTML() {
        $invoiceHTML = new Zend_View();
        $invoiceHTML->setScriptPath(APPLICATION_PATH . '/views/scripts/cv');
     //   $invoiceHTML->assign('invoiceData', $this->invoiceData);
        return $invoiceHTML->render('pdf.phtml');
    }

}