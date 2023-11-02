<?php

class User{
    public $_id;
    public $name;
    public $password;
    public $highscore;
    public $session_id;

    function __construct($_id, $name, $password, $highscore, $session_id)
    {
        $this->_id = $_id;
        $this->name = $name;
        $this->password = $password;
        $this->highscore = $highscore;
        $this->session_id = $session_id;
    }
}