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
      //  var_dump($cvs);
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
        $api = new Application_Model_Api();
        try {
            $response = $api->get('/list-education/');
        } catch (Exception $exc) {
            return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_ERROR, $exc->getMessage());
        }
        $this->view = $this->feedViewAnArray($this->view, 'education', json_decode($response->getBody(), true));
        try {
            $response = $api->get('/list-workplace/');
        } catch (Exception $exc) {
            return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_ERROR, $exc->getMessage());
        }
        $this->view = $this->feedViewAnArray($this->view, 'workplace', json_decode($response->getBody(), true));
        try {
            $response = $api->get('/list-additional-skills/');
        } catch (Exception $exc) {
            return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_ERROR, $exc->getMessage());
        }
        $this->view = $this->feedViewAnArray($this->view, 'additionalSkills', json_decode($response->getBody(), true));
        try {
            $response = $api->get('/list-languages/');
        } catch (Exception $exc) {
            return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_ERROR, $exc->getMessage());
        }
        $this->view = $this->feedViewAnArray($this->view, 'languages', json_decode($response->getBody(), true));
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
        //    var_dump($array);
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

    private function explodeAddFormCvArray($cv) {
        if (!$cv) {
            return $cv;
        }
        if (!is_array($cv)) {
            return $cv;
        }
        foreach ($cv as $key => $value) {

            try {
                $explode = explode('_', $key);
            } catch (Exception $exc) {
                continue;
            }
            switch ($explode[0]) {
                case 'education':
                    if (!isset($cv['education'])) {
                        $cv['education'] = array();
                    }
                    if (intval($value) == 1) {
                        array_push($cv['education'], $explode[1]);
                    }
                    unset($cv[$key]);
                    break;
                case 'workplace':
                    if (!isset($cv['workplace'])) {
                        $cv['workplace'] = array();
                    }
                    if (intval($value) == 1) {
                        array_push($cv['workplace'], $explode[1]);
                    }
                    unset($cv[$key]);
                    break;
                case 'languages':
                    if (!isset($cv['languages'])) {
                        $cv['languages'] = array();
                    }
                    if (intval($value) == 1) {
                        array_push($cv['languages'], $explode[1]);
                    }
                    unset($cv[$key]);
                    break;
                case 'additionalSkills':
                    if (!isset($cv['additionalSkills'])) {
                        $cv['additionalSkills'] = array();
                    }
                    if (intval($value) == 1) {
                        array_push($cv['additionalSkills'], $explode[1]);
                    }
                    unset($cv[$key]);
                    break;

                default:
                    break;
            }
        }
        if (isset($cv['education'])) {
            $cv['education'] = json_encode($cv['education']);
        }
        if (isset($cv['workplace'])) {
            $cv['workplace'] = json_encode($cv['workplace']);
        }
        if (isset($cv['additionalSkills'])) {
            $cv['additionalSkills'] = json_encode($cv['additionalSkills']);
        }
        if (isset($cv['languages'])) {
            $cv['languages'] = json_encode($cv['languages']);
        }
        return $cv;
    }

    public function saveAction() {
        try {
            $form = $this->_helper->Function->filterInputs($this->getAllParams());
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }

        $formData = $this->explodeAddFormCvArray($form);

        $api = new Application_Model_Api();
        try {
            $response = $api->add('/add-cv/', $formData);
        } catch (Exception $exc) {
            return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_ERROR, $exc->getMessage());
        }
        return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_OK, $response->getBody());
//        return $this->_helper->ResponseAjax->response(Application_Model_AjaxResponseCode::CODE_WARN, json_encode($formData));
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
    
    public function removeCvAction() {
        try {
            $form = $this->_helper->Function->filterInputs($this->getAllParams());
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }
        $api = new Application_Model_Api();
        try {
            $response = $api->delete('/remove-cv/', $form['id']);
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
        $myCv['layout'] = $cv['pdf_layout'];
        $date = new Zend_Date($userData['birthdate']);
        $myCv['birthdate'] = $date->toString('d MMMM YYYY');

        $api = new Application_Model_Api();
        if (isset($cv['list_education'])) {
            try {
                $education = $api->get('/list-education/');
            } catch (Exception $exc) {
                throw new Exception($exc->getMessage());
            }
            $myCv['education'] = $this->explodeListToCV($cv, json_decode($education->getBody(), true), 'list_education');
        }
        if (isset($cv['list_workplace'])) {
            try {
                $education = $api->get('/list-workplace/');
            } catch (Exception $exc) {
                throw new Exception($exc->getMessage());
            }
            $myCv['workplace'] = $this->explodeListToCV($cv, json_decode($education->getBody(), true), 'list_workplace');
        }
        if (isset($cv['list_languages'])) {
            try {
                $education = $api->get('/list-languages/');
            } catch (Exception $exc) {
                throw new Exception($exc->getMessage());
            }
            $myCv['languages'] = $this->explodeListToCV($cv, json_decode($education->getBody(), true), 'list_languages');
        }
        if (isset($cv['list_additional_skills'])) {
            try {
                $education = $api->get('/list-languages/');
            } catch (Exception $exc) {
                throw new Exception($exc->getMessage());
            }
            $myCv['additionalSkills'] = $this->explodeListToCV($cv, json_decode($education->getBody(), true), 'list_additional_skills');
        }


        return $myCv;
    }

    private function getElementFromArray($id, $array) {
        if (!$array) {
            return array();
        }
        if (!is_array($array)) {
            return;
        }
        foreach ($array as $value) {
            if ($value['id'] == $id) {
                return $value;
            }
        }
        return array();
    }

    private function explodeListToCV($cv, $array, $name) {
        $output = array();

        // throw new Exception(json_encode(json_decode($cv[$name], true)));

        foreach (json_decode($cv[$name], true) as $v) {

            $e = $this->getElementFromArray($v, $array);
            if (!$e) {
                continue;
            }

            switch ($name) {
                case 'list_languages':
                    array_push($output, array(
                        'name' => $e['name'],
                        'description' => $e['description']
                    ));
                    break;
                case 'list_additional_skills':
                    array_push($output, array(
                        'name' => $e['description']
                    ));
                    break;
                default:
                    $date_of = new Zend_Date($e['date_of']);
                    $e['date_of'] = $date_of->toString('YYY.MM');
                    $date_to = new Zend_Date($e['date_to']);
                    $e['date_to'] = $date_to->toString('YYY.MM');
                    array_push($output, array(
                        'date' => sprintf('%s - %s', $e['date_of'], $e['date_to']),
                        'name' => $e['description']
                    ));
            }
        }
        return $output;
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

        $pdf = new CvPdfGenerator();
        $pdf->setData($cv);
        $pdf->getPDF();
        exit();
    }

    public function testAction() {

        $api = new Application_Model_Api();

        try {
            $response = $api->get('/list-education/');
        } catch (Exception $exc) {
            throw new Exception($exc->getMessage());
        }


        echo '<pre>';
        var_dump($response->getStatus());
        var_dump($response->getMessage());
        var_dump(json_decode($response->getBody(), true));
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
        if (isset($data['layout'])) {
            $this->layout = $data['layout'];
        }
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
        $invoiceHTML->setScriptPath(APPLICATION_PATH . '/views/scripts/cv/pdf-layouts');
        foreach ($this->data as $key => $value) {
            $invoiceHTML->assign($key, $value);
        }
        return $invoiceHTML->render(sprintf('layout-pdf-%s.phtml', $this->layout));
    }

}
