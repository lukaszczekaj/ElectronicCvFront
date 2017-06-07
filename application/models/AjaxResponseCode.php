<?php

/**
 * 
 */
class Application_Model_AjaxResponseCode {

    const CODE_DATA = 100;
    const CODE_ERROR = 101;
    const CODE_OK = 102;
    const CODE_WARN = 103;
    const CODE_DATA_SUCCESS = 104;
    const CODE_SESSION_EXPIRED = 105;

    private function __construct() {
        
    }

    /**
     * Zwraca tablice z polskimi opisującymi nazwami stanów kontrahenta
     * @return array
     */
    public static function getNamesArray() {
        $helper = new Zend_Controller_Action_Helper_Internationalization();
        $t = $helper->loadInternationalization();
        return array(
        );
    }

    /**
     * Pobiera nazwę konkretnego stanu
     * @param type $code
     * @return type
     */
    public static function convertIntToStringRepresentation($code) {
        $allStates = self::getNamesArray();
        if (!array_key_exists($code, $allStates)) {
            return;
        }
        return $allStates[$code];
    }

}
