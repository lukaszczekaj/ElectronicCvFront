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

    public function languagesAction() {
        $this->view->subPage = 'Języki';
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
        return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_WARN, 'Metoda jeszcze nie wspierana '. json_encode($form));
    }

    public function addEducationAction() {
        try {
            $form = $this->_helper->Function->filterInputs($this->getAllParams());
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }
        return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_WARN, 'Metoda jeszcze nie wspierana '. json_encode($form));
    }

    public function addWorkplaceAction() {
        try {
            $form = $this->_helper->Function->filterInputs($this->getAllParams());
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }
        return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_WARN, 'Metoda jeszcze nie wspierana '. json_encode($form));
    }

    public function addAdditionalSkillsAction() {
        try {
            $form = $this->_helper->Function->filterInputs($this->getAllParams());
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }
        return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_WARN, 'Metoda jeszcze nie wspierana '. json_encode($form));
    }

    public function pdfAction() {

        $data = array(
            'name' => 'Łukasz Czekaj',
            'maritalStatus' => 'Kawaler',
            'birthDate' => '1 czerwiec 1991',
            'birthPlace' => 'Kielce',
            'addressStreet' => 'Lechówek 47A',
            'addressPost' => '26-025 Łagów',
            'phone' => '874587344',
            'mail' => 'lukasz@lukaszczekaj.pl',
            'education' => array(
                array(
                    'date' => '2011 - nadal',
                    'name' => 'Studia Inżynierskie na Politechnice Świętokrzyskiej w Kielcach, kierunek informatyka'
                ),
                array(
                    'date' => '2007 - 2011',
                    'name' => 'Zespół Szkół Informatycznych im. gen. Józefa Hauke Bosaka w Kielcach, tytuł technika informatyka'
                )
            ),
            'workplace' => array(
                array(
                    'date' => '07.2013',
                    'name' => 'Miesięczne praktyki zawodowe w firmie Complex Computers Sp. z o.o. w Kielcach'
                ),
                array(
                    'date' => '05.2012',
                    'name' => 'Uczestnictwo w kursie CCNA Exploration: Network Fundamentals'
                ),
                array(
                    'date' => '06.2009 – 10.2010',
                    'name' => 'Uczestnictwo w szkoleniu „Uczeń z międzynarodowym certyfikatem zawodowym- modelowe rozwiązanie dla świętokrzyskich pracodawców” realizowane przez COMBIDATA POLAND Sp. z o.o.'
                )
            ),
            'languages' => array(
                array(
                    'name' => 'j. angielski',
                    'description' => '- poziom średni w mowie i piśmie'
                )
            ),
            'additionalSkills' => array(
                array(
                    'name' => 'szybkie nawiązywanie kontaktów, umiejętność współpracy w zespole'
                ),
                array(
                    'name' => 'bardzo dobra obsługa komputera, znajomość systemów operacyjnych Windows oraz urządzeń peryferyjnych komputera'
                ),
                array(
                    'name' => 'bardzo dobra znajomość zagadnień związanych z sieciami komputerowymi'
                ),
                array(
                    'name' => 'prawo jazdy kat. B.'
                )
            ),
            'interests' => 'muzyka, sport, elektronika'
        );

        $pdf = new CvPdfGenerator();
        $pdf->setData($data);
        $pdf->getPDF();
        exit();
    }
    
}

/**
 * Wygenerowanie PDFa faktury
 */
class CvPdfGenerator {

    private $data = array();
    private $layout = 1;

    public function setData($data) {
        $this->data = $data;
    }

    public function setLayout($layout) {
        $this->layout = $layout;
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
        foreach ($this->data as $key => $value) {
            $invoiceHTML->assign($key, $value);
        }
        return $invoiceHTML->render(sprintf('layout-pdf-%s.phtml', $this->layout));
    }

}
