<?php

/**
 * 
 */
class Application_Model_AppRole {

    private function __construct() {
        
    }

    public static function getUserRole() {
        $api = new Application_Model_Api();
        try {
            $response = $api->fetchUserData();
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }
        $userData = json_decode($response->getBody(), true);
        try {
            $appRoleResponse = $api->get('/list-app-roles/');
        } catch (Exception $exc) {
            Application_Model_Exception::exception($this->_helper, $this->getAllParams(), $exc);
        }
        $appRole = json_decode($appRoleResponse->getBody(), true);
        $role = self::getElementFromArrayByKeyName($appRole, 'id', $userData['user']['approleid']);
        if ($role) {
            return $role['rolename'];
        }
        return;
    }

    private function getElementFromArrayByKeyName($array, $keyName, $keyValue) {
        foreach ($array as $value) {
            if ($value[$keyName] == $keyValue) {
                return $value;
            }
        }
        return array();
    }

}
