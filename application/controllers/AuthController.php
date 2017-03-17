<?php

/**
 * Kontroler obsługujący autoryzacje dostepu do strony
 */
class AuthController extends Zend_Controller_Action {

    /**
     * Zmienna do widoków
     * @var string 
     */
    private $viewHelper;

    public function init() {
        $this->viewHelper = new Zend_View_Helper_Action();
    }

    public function indexAction() {
        
    }

    public function logonAction() {
        
    }

    /**
     * Generuje losowy ciag znaków
     * @param  integer $len Długość
     * @return string Wygenerowany ciąg znaków
     */
    private function generateRandomString($len) {
        $rstr = '';
        for ($i = 0; $i < $len; $i++) {
            $p = rand(0, 2);
            switch ($p) {
                case(0): $rstr .= chr(rand(ord('0'), ord('1')));
                    break;
                case(1): $rstr .= chr(rand(ord('A'), ord('Z')));
                    break;
                case(2): $rstr .= chr(rand(ord('a'), ord('z')));
                    break;
            }
        } return $rstr;
    }

    /**
     * Reset hasla usera
     * @return type
     * @throws Exception
     */
    public function passwordRecoveryAction() {
        
    }

    /**
     * Wylogowanie z systemu
     */
    public function logoutAction() {
        
    }

    /**
     * Sprawdza czy istnieje taki adres e-mail
     */
    public function checkExistMailAction() {
        
    }

    /**
     * Aktualizacja daty logowania usera
     */
    private function updateLastUserLogonDate() {
        
    }

    /**
     * Przedluzenie sesji uzytkownika
     * @return type
     */
    public function extensionLogonSessionAction() {
        
    }

}
