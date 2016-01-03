<?php
require APPPATH . '/libraries/REST_Controller.php';
class General_api extends REST_Controller {

    function Pagenotfound(){
            $this->response(array("status" => "404","msg" => "Page not found"));
        }

}