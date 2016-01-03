<?php
require APPPATH . '/libraries/REST_Controller.php';
class User_api extends REST_Controller {
//class User_api extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('form_validation');
    }

    function Updateuser_post(){
        $id = $this->uri->segment(4);
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('phone', 'Phone Number', 'required');
        $this->form_validation->set_error_delimiters('', '');
        if($this->form_validation->run() === FALSE){
            $errors =  validation_errors();
            $error_array=explode(".",$errors);
            print_r($error_array);
        }else{
            $this->User_model->update( $id, array(
                'name' => $this->post('name'),
                'phone' => $this->post('phone')
            ));

            if ($this->db->affected_rows() > 0) {
                //echo json_encode(array("status" => 200,"msg" => "User updated successfully"));
                $updateduser_details=$this->User_model->get_by_id($id);
                $this->response(array("status" => "200","msg" => "User updated successfully","data" => $updateduser_details));
            } else {
                //echo json_encode(array("status" => 404,"msg" => "Already updated"));
                $this->response(array("status" => "404","msg" => "Already updated"));
            }

         }
    }


    function Users_get(){
        $data=$this->User_model->get_all();
        if($data){
            //echo json_encode(array("status" => 200,"details" => $data));
            $this->response(array("status" => "200","msg" => "Success","data" => $data));
        }else{
            //echo json_encode(array("status" => "404","msg" => "User doesnot exist"));
            $this->response(array("status" => "404","msg" => "Sorry no records found"));
        }
    }

    function Showuser_get(){
        $id = $this->uri->segment(4);
        $data=$this->User_model->get_by_id($id);
        if($data){
            //echo json_encode(array("status" => 200,"details" => $data));
            $this->response(array("status" => "200","msg" => "Success","data" => $data));
        }else{
            //echo json_encode(array("status" => 404,"msg" => "User doesnot exist"));
            $this->response(array("status" => "404","msg" => "User doesnot exist"));
        }
    }

    function Adduser_post(){
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('phone', 'Phone Number', 'required');
        $this->form_validation->set_error_delimiters('', '');
        if($this->form_validation->run() === FALSE){
            $errors =  validation_errors();
            $error_array=explode(".",$errors);
            print_r($error_array);
        }else{
            $data = array( // inputs to validate
                'name'=> $this->input->post('name'),
                'phone' => $this->input->post('phone')
            );
            $this->db->insert('users', $data);
            if($data){
                $insert_id = $this->db->insert_id();
                $insert_id_data = array(
                    'insert_ID'=> $insert_id
                );
                $merge_data = array_merge($data,$insert_id_data);
                //echo json_encode(array("status" => 200,"details" => $merge_data));
                $this->response(array("status" => "200","msg" => "User created successfully","data" => $merge_data));

            }else{
                //echo json_encode(array("status" => 404,"msg" => "Cannot create user"));
                $this->response(array("status" => "404","msg" => "Cannot create user"));
            }
        }
    }

    function Deleteuser_delete(){
        $id = $this->uri->segment(4);
        $user_details=$this->User_model->get_by_id($id);
        $this->User_model->delete_data($id);
        if ($user_details){
            //echo json_encode(array("status" => 200,"details" => $user_details));
            $this->response(array("status" => "200","msg" => "User deleted successfully","data" => $user_details));
        }else{
            //echo json_encode(array("status" => 404,"msg" => "Already Deleted"));
            $this->response(array("status" => "404","msg" => "User already deleted"));
        }
    }





}