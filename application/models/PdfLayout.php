<?php

/**
 * 
 */
class Application_Model_PdfLayout {

    const LAYOUT_1 = 1;

    private function __construct() {
        
    }

    /**
     * Zwraca tablice z polskimi opisującymi nazwami stanów kontrahenta
     * @return array
     */
    public static function getNamesArray() {
        return array(
            self::LAYOUT_1 => 'Wygląd 1'
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
