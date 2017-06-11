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
        $api = new Application_Model_Api();
        try {
            $response = $api->get('/list-cv/');
        } catch (Exception $exc) {
            return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_ERROR, $exc->getMessage());
        }
        $this->view = $this->feedViewCv($this->view, json_decode($response->getBody(), true));
    }

    private function feedViewCv($view, $cvs) {
        $view->cvs = array();
        var_dump($cvs);
        if (!$cvs || !is_array($cvs)) {
            return $view;
        }
        foreach ($cvs as $key => $value) {
            
        }
        $this->view->cvs = $cvs;
        return $view;
    }

    public function addAction() {
        $this->view->subPage = 'Dodaj CV';
    }

    public function educationAction() {
        $this->view->subPage = 'Wykrztałcenie';
        $api = new Application_Model_Api();
        try {
            $response = $api->get('/list-education/');
        } catch (Exception $exc) {
            return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_ERROR, $exc->getMessage());
        }
        $this->view = $this->feedViewAnArray($this->view, 'education', json_decode($response->getBody(), true));
    }

    private function feedViewAnArray($view, $name, $array) {
        $view->{$name} = array();
        if (!$array || !is_array($array)) {
            return $view;
        }
        foreach ($array as $key => $value) {
            $array[$key]['date_of'] = '';
            if (isset($value['date_of'])) {
                $dateOf = new Zend_Date($value['date_of']);
                $array[$key]['date_of'] = $dateOf->toString('yyyy-MM-dd');
            }
            $array[$key]['date_to'] = '';
            if (isset($value['date_to'])) {
                $dateTo = new Zend_Date($value['date_to']);
                $array[$key]['date_to'] = $dateTo->toString('yyyy-MM-dd');
            }
            $array[$key]['date'] = '';
            if (isset($value['date'])) {
                $date = new Zend_Date($value['date']);
                $array[$key]['date'] = $date->toString('yyyy-MM-dd');
            }
        }
        $this->view->{$name} = $array;
        return $view;
    }

    public function workplaceAction() {
        $this->view->subPage = 'Miejsce pracy';
        $api = new Application_Model_Api();
        try {
            $response = $api->get('/list-workplace/');
        } catch (Exception $exc) {
            return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_ERROR, $exc->getMessage());
        }
        $this->view = $this->feedViewAnArray($this->view, 'workplace', json_decode($response->getBody(), true));
    }

    public function additionalSkillsAction() {
        $this->view->subPage = 'Dodatkowe umiejętności';
        $api = new Application_Model_Api();
        try {
            $response = $api->get('/list-additional-skills/');
        } catch (Exception $exc) {
            return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_ERROR, $exc->getMessage());
        }
        $this->view = $this->feedViewAnArray($this->view, 'additionalSkills', json_decode($response->getBody(), true));
    }

    public function languagesAction() {
        $this->view->subPage = 'Języki';
        $api = new Application_Model_Api();
        try {
            $response = $api->get('/list-languages/');
        } catch (Exception $exc) {
            return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_ERROR, $exc->getMessage());
        }
        $this->view = $this->feedViewAnArray($this->view, 'languages', json_decode($response->getBody(), true));
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
        $api = new Application_Model_Api();
        try {
            $response = $api->add('/add-cv/', $form);
        } catch (Exception $exc) {
            return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_ERROR, $exc->getMessage());
        }
        return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_OK, $response->getBody());
    }

    public function addEducationAction() {
        try {
            $form = $this->_helper->Function->filterInputs($this->getAllParams());
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }
        $api = new Application_Model_Api();
        try {
            $response = $api->add('/add-education/', $form);
        } catch (Exception $exc) {
            return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_ERROR, $exc->getMessage());
        }
        return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_OK, $response->getBody());
    }

    public function addLanguagesAction() {
        try {
            $form = $this->_helper->Function->filterInputs($this->getAllParams());
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }
        $api = new Application_Model_Api();
        try {
            $response = $api->add('/add-languages/', $form);
        } catch (Exception $exc) {
            return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_ERROR, $exc->getMessage());
        }
        return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_OK, $response->getBody());
    }

    public function removeEducationAction() {
        try {
            $form = $this->_helper->Function->filterInputs($this->getAllParams());
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }
        $api = new Application_Model_Api();
        try {
            $response = $api->delete('/remove-education/', $form['id']);
        } catch (Exception $exc) {
            return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_ERROR, $exc->getMessage());
        }
        return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_OK, $response->getBody());
    }

    public function removeLanguagesAction() {
        try {
            $form = $this->_helper->Function->filterInputs($this->getAllParams());
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }
        $api = new Application_Model_Api();
        try {
            $response = $api->delete('/remove-languages/', $form['id']);
        } catch (Exception $exc) {
            return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_ERROR, $exc->getMessage());
        }
        return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_OK, $response->getBody());
    }

    public function removeWorkplaceAction() {
        try {
            $form = $this->_helper->Function->filterInputs($this->getAllParams());
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }
        $api = new Application_Model_Api();
        try {
            $response = $api->delete('/remove-workplace/', $form['id']);
        } catch (Exception $exc) {
            return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_ERROR, $exc->getMessage());
        }
        return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_OK, $response->getBody());
    }

    public function removeAdditionalSkillsAction() {
        try {
            $form = $this->_helper->Function->filterInputs($this->getAllParams());
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }
        $api = new Application_Model_Api();
        try {
            $response = $api->delete('/remove-additional-skills/', $form['id']);
        } catch (Exception $exc) {
            return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_ERROR, $exc->getMessage());
        }
        return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_OK, $response->getBody());
    }

    public function addWorkplaceAction() {
        try {
            $form = $this->_helper->Function->filterInputs($this->getAllParams());
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }
        $api = new Application_Model_Api();
        try {
            $response = $api->add('/add-workplace/', $form);
        } catch (Exception $exc) {
            return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_ERROR, $exc->getMessage());
        }
        return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_OK, $response->getBody());
    }

    public function addAdditionalSkillsAction() {
        try {
            $form = $this->_helper->Function->filterInputs($this->getAllParams());
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }
        $api = new Application_Model_Api();
        try {
            $response = $api->add('/add-additional-skills/', $form);
        } catch (Exception $exc) {
            return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_ERROR, $exc->getMessage());
        }
        return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_OK, $response->getBody());
    }

    private function explodeCvData($cv, $userData) {
        if (!$cv || !$userData) {
            throw new Exception('Brak kompletnych danych');
        }
        $myCv = $userData;
        $myCv['name'] = sprintf('%s %s', $userData['firstname'], $userData['lastname']);
        
        return $myCv;
    }

    public function pdfAction() {
        $api = new Application_Model_Api();
        try {
            $getCV = $api->getCv($this->getParam('id'));
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }
        try {
            $userData = $api->fetchUserData();
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }
        try {
            $cv = $this->explodeCvData(json_decode($getCV->getBody(), true), json_decode($userData->getBody(), true));
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }


        $data = array(
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
            )
        );

        $pdf = new CvPdfGenerator();
        $pdf->setData($cv);
        $pdf->getPDF();
        exit();
    }

    public function testAction() {

        $api = new Application_Model_Api();

        try {
            $response = $api->login(array(
                'mail' => 'asdsad',
                'password' => 'asdasd'
            ));
        } catch (Exception $exc) {
            var_dump($exc->getMessage());
        }



        var_dump($response->getStatus());
        var_dump($response->getMessage());
        var_dump($response->getBody());
        var_dump($response);
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
