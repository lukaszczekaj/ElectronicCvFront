<?php

/**
 * Przeysła odpowiedź do klienta w formacie JSON
 */
class Zend_Controller_Action_Helper_Function extends Zend_Controller_Action_Helper_Abstract {

    public function filterInputs($inputs) {
        if (!isset($inputs['data']) || empty($inputs['data'])) {
            throw new Exception('Niepoprawne parametry wejściowe.');
        }
        return $inputs['data'];
    }

}

?>
