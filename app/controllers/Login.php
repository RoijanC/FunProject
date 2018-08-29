<?php
class Login extends Controller{
	public function index(){
		$user = $this->model('User');
		if(isset($_POST['action']) && $_POST['action'] == 'Login'){
			$username = $_POST['username'];
			$password = $_POST['password'];
			LoginCore::login($username, $password);
			header('location:/example/someMethod');
		}else
			$this->view('Login/index');
	}


	public function Signup(){
		$user = $this->model('User');
		if(isset($_POST['action']) && $_POST['action'] == 'Login'){
			$user->username = $_POST['username'];
			$user->password = password_hash($_POST['password'],PASSWORD_DEFAULT);
			$user->insert();

			
			header('location:/Login');
		}else
			$this->view('Login/index');
	}

	
	public function doLogout(){
		LoginCore::logout();
		header('location:/Login');
	}
}
?>