<?php

class Client extends Model{
	public $firstName;
	public $lastName;
	public $email;
	public $phone;
	public $country;

	public function __construct(){
		parent::__construct();
	}
	
	function isValid(){
		return ($this->firstName!='' && $this->country != '');
	}
}
?>