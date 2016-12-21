<?php
class PostController extends Controller {

	public $_post;

	public function filters() {
		return array(
			'checkAndSetUser + view, comments, delete, update',
			);
	}

	public function filterCheckAndSetUser($filterChain) {
		if(!$_GET['id'])
			$this->renderError("Invalid Data!");
		else {
			$this->_post = Post::model()->active()->findByPk($_GET['id']);
			if(!$this->_post)
				$this->renderError("Invalid Data!");			
		}
		$filterChain->run();
	}

	public function actionCreate() {
		if(isset($_POST['Post'])) {
			$post = Post::create($_POST['Post']);
			if(!$post->errors) {
				$this->renderSuccess(array('post_id'=>$post->id,'content'=>$post->content));
			} else {
				$this->renderError($this->getErrorMessageFromModelErrors($post));
			}
		} else {
			$this->renderError('Please send post data!!');
		}
	}

	public function actionView($id) {
		$this->renderSuccess(array('status'=>'SUCCESS', 'id'=>$this->_post->id,'content'=>$this->_post->content));
	}

	public function actionNewsfeeds() {
		$posts = Post::model()->active()->findAll(array('order'=> 'created_at DESC', 'limit'=> 10));

		if(!$posts) {
			$this->renderError('There is no posts to show');
		}
		else{

			$posts_data = array();

			foreach ($posts as $post) {

				$posts_data[] = array('id'=>$post-> id, 'content'=>$post->content, 'user_name'=>$post-> user-> name);
				
			}
			$this->renderSuccess(array('status'=> 'SUCCESS',
				'posts_data'=> $posts_data
				));
		}
	}


	public function actionSearch($str){


		$posts = Post::model()->active()->findAll(array('condition'=> "content LIKE :str", 'params'=> array('str'=>"%$str%")));
		if(!$posts) {

			$this->renderError('There is no posts to show which has '.$str);
		}
		else{
			$posts_data = array();
			foreach ($posts as $post) {

				$posts_data[] = array('id'=>$post-> id, 'content'=>$post-> content);

			}
			$this->renderSuccess(array('status'=> 'SUCCESS',

				'posts_data'=> $posts_data,
				));

		}
	}


	public function actionComments($id) {

		$comments = $this->_post->comments;
		foreach ($comments as $comment) {
			if($comment->status==1){
				$this->renderSuccess(array('status'=>'SUCCESS', 'user_id'=>$comment->user_id, 'user_name'=>$comment->user->name, 'content'=>$comment->content));
			}	
		}

	}

	public function actionDelete($id){

		$this->_post->status = 2;
		$this->_post->save();
		$this->renderSuccess(array('Deleted Successfully'));
	}

	public function actionRestore($id){

		$post = Post::model()->findByPk($id);
		$post->status = 1;
		$post->save();
	}

	public function actionUpdate($str, $id){


		$this->_post->content = $str;
		$this->_post->save();
		$this->renderSuccess(array('Updated Successfully'));


	}

}