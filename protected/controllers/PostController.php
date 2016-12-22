<?php
class PostController extends Controller {
	public $_post;

	public function filters() {
		return array(
			'checkAndSetPost + view, comments, delete, update, topComments, likes,',
			);
	}

	public function filterCheckAndSetPost($filterChain) {
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
				$this->renderSuccess(array('post_id'=>$post->id,'content'=>$post->content, 'created_at'=>$post->created_at));
			} else {
				$this->renderError($this->getErrorMessageFromModelErrors($post));
			}
		} else {
			$this->renderError('Please send post data!!');
		}
	}

	public function actionView($id) {
		$this->renderSuccess(array('id'=>$this->_post->id,'content'=>$this->_post->content));
	}

	public function actionNewsfeeds() {
		$posts = Post::model()->active()->findAll(array('order'=> 'created_at DESC', 'limit'=>10));
		if(!$posts) {
			$this->renderError('There is no posts to show');
		}
		else{
			$posts_data = array();
			foreach ($posts as $post) {
				$posts_data[] = array('id'=>$post->id, 'content'=>$post->content, 'user_name'=>$post->user->name);				
			}
			$this->renderSuccess(array('posts_data'=> $posts_data));
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
				$posts_data[] = array('id'=>$post-> id, 'content'=>$post->content);
			}
			$this->renderSuccess(array('posts_data'=> $posts_data,));
		}
	}

	public function actionTopComments($id){
		$comments_data = array();
		foreach ($this->_post->comments(array('scopes'=>'active', 'order'=>'created_at DESC', 'limit'=>5)) as $comment) {
			$comments_data[] = array('user_name'=>$comment->user->name, 'content'=>$comment->content, 'created_at'=>$comment->created_at);
		}
		$this->renderSuccess(array('comments'=>$comments_data));
	}

	public function actionComments($id) {
		$comments_data=array();
		$number_of_comments = 0;
		foreach ($this->_post->comments('comments:active') as $comment) {
			$number_of_comments++;
			$comments_data[] = array('user_id'=>$comment->user_id, 'user_name'=>$comment->user->name, 'content'=>$comment->content);
		}
		$this->renderSuccess(array('number_of_comments'=> $number_of_comments,'comments'=>$comments_data));
	}

	public function actionLikes($id) {
		$likes_data=array();
		$number_of_likes = 0;
		foreach ($this->_post->likes(array('scopes'=>'active')) as $like) {
			$number_of_likes++;
			$likes_data[] = array('user_id'=>$like->user_id, 'user_name'=>$like->user->name);
		}
		$this->renderSuccess(array('number_of_likes'=> $number_of_likes,'likes'=>$likes_data));
	}

	public function actionDelete($id){
		$this->_post->remove();
		$this->renderSuccess(array('Post_id'=>$this->_post->id,'Message'=>'Deleted Successfully'));
	}

	public function actionUpdate($str, $id){
		$this->_post->content = $str;
		$this->_post->save();
		$this->renderSuccess(array('Post_id'=>$this->_post->id, 'Updated_at'=>$this->_post->updated_at,'Message'=>'Updated Successfully'));
	}
}