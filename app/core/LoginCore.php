<?php
class LoginCore{
	public static function login($username, $password){
		$user = Controller::model('User');
		$users = $user->where(['username'=>$username]);
		
		if(isset($users[0])){
			if(password_verify($password, $users[0]->password)){
				$_SESSION['username'] = $username;
				$_SESSION['userID'] = $users[0]->ID;
			}
		}
	}
	
	public static function isLoggedIn(){
		return isset($_SESSION['username']);
	}
	
	public static function logout(){
		unset($_SESSION['username']);
		unset($_SESSION['userID']);
	}
}
?>