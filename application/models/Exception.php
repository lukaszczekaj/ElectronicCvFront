<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'Zend/Auth/Result.php';
/**
 * Description of MyException
 *
 * @author lukasz
 */
class Application_Model_Exception {

    public static function exception($thisHelper, $params, Exception $exc) {
        if (filter_var($params['ajaxAction'], FILTER_SANITIZE_STRING) != '1') {
            throw new Exception($exc->getMessage(), $exc->getCode());
        } else {
            return $thisHelper->ResponseAjax->response(($exc->getCode() == 403) ? Application_Model_AjaxResponseCode::CODE_SESSION_EXPIRED : Application_Model_AjaxResponseCode::CODE_ERROR, array('msg' => $exc->getMessage()));
        }
    }

}
