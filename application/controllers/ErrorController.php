<?php

class ErrorController extends Zend_Controller_Action {

    public function init() {
        $this->_helper->layout()->disableLayout();  //nie renderuje widoku calego layoutu dla innych akcji
    }

    public function errorAction() {
        $errors = $this->_getParam('error_handler');

        if (!$errors || !$errors instanceof ArrayObject) {
            $this->view->message = 'Osiągnięto stronę błędu';
            return;
        }

        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->code = 404;
                $priority = Zend_Log::NOTICE;
                $this->view->message = 'Strona nieznaleziona';
                break;
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER:
                if ($errors->exception->getCode() != 0) {
                    $this->getResponse()->setHttpResponseCode($errors->exception->getCode());
                }
                $this->view->message = $errors->exception->getMessage();
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $priority = Zend_Log::CRIT;
                $this->view->code = 500;
                $this->view->message = 'Błąd aplikacji';
                break;
        }

        // Log exception, if logger available
        if ($log = $this->getLog()) {
            $log->log($this->view->message, $priority, $errors->exception);
            $log->log('Request Parameters', $priority, $errors->request->getParams());
        }

        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }

        $this->view->request = $errors->request;
    }

    public function getLog() {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }

}
