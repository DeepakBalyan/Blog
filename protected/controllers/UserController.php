<?php
class UserController extends Controller {

	public $_user;

	public function filters() {
		return array(
			'checkAndSetUser +  login, profile, deactivate',
			'activateAccount + activate',
			);
	}

	public function filterCheckAndSetUser($filterChain) {
		if(!$_GET['id'])
			$this->renderError("Invalid Data!");
		else {
			$this->_user = User::model()->active()->findByPk($_GET['id']);
			if(!$this->_user)
				$this->renderError("Invalid Data!");      
		}
		$filterChain->run();
	}

	public function filterActivateAccount($filterChain){
		if(!$_GET['id'])
			$this->renderError("Invalid Data!");
		else {

			$this->_user = User::model()->deactivated()->findByPk($_GET['id']);
			if(!$this->_user)
				$this->renderError("Invalid Data!");  
		}

		$filterChain->run();
	}

	public function actionCreate() {
		if(isset($_POST['User'])) {
			$user = User::create($_POST['User']);
			if(!$user->errors) {
				$this->renderSuccess(array('user_id'=>$user->id,'created_at'=>$post->created_at));
			}
			else {
				$this->renderError($this->getErrorMessageFromModelErrors($user));
			}
		}
		else {
			$this->renderError('Please send post data!');
		}
	}

	public function actionLogin($id){
		$this->renderSuccess(array('name'=>$this->_user->name,'email'=>$this->_user->email));
	}

	public function actionProfile($id) {
		$this->renderSuccess(array('name'=>$this->_user->name, 'email'=>$this->_user->email));
	}

	public function actionSearchProfile($name){
		$users = User::model()->active()->findAllByAttributes(array('name'=>$name));
		if(!$users){
			$this->renderError('Account does not exits');
		}
		else{
			$users_profile = array();
			foreach($users as $user){
				$users_profile[] = array('user_id'=>$user->id, 'user_name'=>$user->name, 'email'=>$user->email);
			}
			$this->renderSuccess(array('status'=>'SUCCESS', 'users_profile'=>$users_profile));
		}
	}

	public function actionDeactivate($id){
		$this->_user->deactivate();
		$this->renderSuccess(array('User_id'=>$this->_user->id,'Message'=>'Deactivated Successfully'));
	}

	public function actionActivate($id){
		$this->_user->activate();
		$this->renderSuccess(array('User_id'=>$this->_user->id,'Message'=>'Activated Successfully'));
	}
}