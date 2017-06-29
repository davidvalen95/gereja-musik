<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Controller{


  public function index(){

    $this->load->helper("CFormEntity_helper");

    $name         = new CFormEntity("Full Name","name","text","glyphicon-user");
    $email        = new CFormEntity("Email","email","email","glyphicon-envelope");
    $optionMusik  = array(
      "gitar"=>"Gitar",
      "drum"=>"Drum",
      "bass" => "Bass",
      "keyboard" => "Keyboard"
    );
    $instrument   = new CFormEntity("Instrument", "instrument", "select", "glyphicon-music",  $optionMusik);
    $password     = new CFormEntity("Password","passwd","password","glyphicon-lock");
    $passwordR    = new CFormEntity("Retype password","retype","password","glyphicon-lock");

    $registCode   = new CFormEntity("Registration Code","registrationCode","text","glyphicon-tag");



    $forms = array($name, $email, $instrument, $password, $passwordR, $registCode);


    $data['title'] = "register";
    $data['forms'] = $forms;
    $this->load->view('template/header',$data);
		$this->load->view('register/register_form',$data);
    // $this->load->view('template/footer',$data);


  }



}


 ?>
