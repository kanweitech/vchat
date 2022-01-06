<?php
	class User{
		public $db, $userID;

		public function __construct() {
			$db = new DB;
			$this->db = $db->connect();
			$this->userID = $this->ID();
		}

		public function ID(){
			if($this->isLoggedIn()){
				return $_SESSION['userID'];
			}
		}

		public function emailExist($email){
			$stmt = $this->db->prepare("SELECT * FROM `users` WHERE `email` = :email");
			$stmt->bindParam(":email", $email, PDO::PARAM_STR);
			$stmt->execute();
			$user = $stmt->fetch(PDO::FETCH_OBJ);

			if(!empty($user)){
				return $user;

			}else{
				return false;
			}
		}

		public function hash($password){
			return password_hash($password, PASSWORD_DEFAULT);
		}

		public function redirect($location){
			header("Location: ".BASE_URL.$location);
		}

		public function userData($userID = ''){
			$userID = ((!empty($userID)) ? $userID : $this->userID);
			$stmt = $this->db->prepare("SELECT * FROM `users` WHERE `userID` = :userID");
			$stmt->bindParam(":userID", $userID, PDO::PARAM_STR);
			$stmt->execute();
			return $stmt->fetch(PDO::FETCH_OBJ);
		}

		public function isLoggedIn(){
			return ((isset($_SESSION['userID'])) ? true : false);
		}

		public function logout(){
			$_SESSION = array();
			session_destroy();
			session_regenerate_id();
			$this->redirect('index.php');
		}
	}

?>
