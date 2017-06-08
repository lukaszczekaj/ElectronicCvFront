<?php

/**
 * Przeysła odpowiedź do klienta w formacie JSON
 */
class Zend_Controller_Action_Helper_ResponseAjax extends Zend_Controller_Action_Helper_Abstract {

    /**
     * Przeysła odpowiedź do klienta w formacie JSON
     * @ajax
     * @return ajax
     */
    public function response($code = Application_Model_AjaxResponseCode::CODE_OK, $data = null) {
        $layout = Zend_Layout::getMvcInstance();
        $layout->disableLayout();
        $this->getActionController()->getHelper('ViewRenderer')->setNoRender(true);
        if (!is_null($data) && !is_array($data)) {
            $data = array('msg' => $data);
        }
        echo json_encode(array('success' => true, 'code' => $code, 'data' => $data));
    }

    public function responseJSON($json) {
        $layout = Zend_Layout::getMvcInstance();
        $layout->disableLayout();
        $this->getActionController()->getHelper('ViewRenderer')->setNoRender(true);
        echo $json;
    }

    /**
     * Wyłącza renderowanie skryptu w views od akcji
     */
    public function responseNone() {

        $this->getActionController()->getHelper('ViewRenderer')->setNoRender(true);
    }

    /**
     * Wysyła proste dane, tutaj od tabelek
     * @param type $data
     * @ajax
     * @return
     * @exit
     */
    public function responseDataTable($data) {
        $layout = Zend_Layout::getMvcInstance();
        $layout->disableLayout();
        $this->getActionController()->getHelper('ViewRenderer')->setNoRender(true);
        echo json_encode($data);
    }

    public function responseView($view) {
        $layout = Zend_Layout::getMvcInstance();
        $layout->disableLayout();
        $this->getActionController()->getHelper('ViewRenderer')->setNoRender(true);
        echo $view;
    }

    /**
     * Przeysła odpowiedź do klienta w formacie JSON tylko true że się powiodło
     * @ajax
     * @return
     * @exit
     */
    public function responseTrue() {
        $layout = Zend_Layout::getMvcInstance();
        $layout->disableLayout();
        $this->getActionController()->getHelper('ViewRenderer')->setNoRender(true);
        echo json_encode(array('success' => true, 'code' => Application_Model_AjaxResponseCode::CODE_OK, 'data' => array('title' => 'Pozytywnie', 'text' => 'Operacja powiodła się')));
    }

    /**
     * Przeysła odpowiedź  o braku uprawnień
     * @ajax
     * @return
     * @exit
     */
    public function responseAccessDenied() {
        $layout = Zend_Layout::getMvcInstance();
        $layout->disableLayout();
        $this->getActionController()->getHelper('ViewRenderer')->setNoRender(true);
        echo json_encode(false);
    }

    /**
     * Teraz nieużywana metoda do sprawdzenia błędu metody json_encode
     * @return string
     */
    private function errorJSON() {

        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return ' - No errors';
            case JSON_ERROR_DEPTH:
                return ' - Maximum stack depth exceeded';
            case JSON_ERROR_STATE_MISMATCH:
                return ' - Underflow or the modes mismatch';
            case JSON_ERROR_CTRL_CHAR:
                return ' - Unexpected control character found';
            case JSON_ERROR_SYNTAX:
                return ' - Syntax error, malformed JSON';
            case JSON_ERROR_UTF8:
                return ' - Malformed UTF-8 characters, possibly incorrectly encoded';
            default:
                return ' - Unknown error';
        }
    }

    public function responseNoneLayout() {
        $layout = Zend_Layout::getMvcInstance();
        $layout->disableLayout();
        $this->getActionController()->getHelper('ViewRenderer')->setNoRender(true);
    }

}

?>
