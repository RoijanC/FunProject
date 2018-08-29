<?php

class example extends Controller{
	function someMethod(){
		$aClient = $this->model('Client');
		$myClients = $aClient->findAll();

		$this->view('example/index',['clients'=>$myClients]);

	}

	function newClient(){
		$newClient = $this->model('Client');
		$newClient->firstName = $_POST['firstName'];
		$newClient->lastName = $_POST['lastName'];
		$newClient->email = $_POST['email'];
		$newClient->phone = $_POST['phone'];
		$newClient->country = $_POST['country'];

		$newClient->insert();
		header("location:/example/someMethod");

	}

	function delete($id){
		$aClient = $this->model('Client');
		$aClient = $aClient->find($id);
		$aClient->delete();
		header("location:/example/someMethod");

	}

}
?>