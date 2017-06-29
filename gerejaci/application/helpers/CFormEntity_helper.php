<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CFormEntity{

    public $placeholder;
    public $name;
    public $icon;
    public $type;
    public $options = array();

    function __construct($placeholder, $name, $type, $icon, $options = array()){
        $this->placeholder = $placeholder;
        $this->name = $name;
        $this->icon = $icon;
        $this->type = $type;
        $this->options = $options;
    }



}


?>
