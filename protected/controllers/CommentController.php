<?php
class CommentController extends Controller {
	public $_comment;

	public function filters() {
		return array(
			'checkAndSetComment + delete, update',
			);
	}

	public function filterCheckAndSetComment($filterChain) {
		if(!$_GET['id'])
			$this->renderError("Invalid Data!");
		else {
			$this->_comment = Comment::model()->active()->findByPk($_GET['id']);
			if(!$this->_comment)
				$this->renderError("Invalid Data!");      
		}
		$filterChain->run();
	}

	public function actionCreate() {
		if(isset($_POST['Comment'])) {
			$comment = Comment::create($_POST['Comment']);
			if(!$comment-> errors) {
				$this->renderSuccess(array('post_id'=>$comment->post_id, 'user_id'=>$comment->user_id, 'content'=>$comment->content, 'created_at'=>$comment->created_at));
			}
			else {
				$this->renderError($this->getErrorMessageFromModelErrors($comment));
			}
		} 
		else {

			$this-> renderError('Please send post data!');
		}
	}

	public function actionDelete($id){
		$this->_comment->remove($id);
		$this->renderSuccess(array('Comment_id'=>$this->_comment->id,'Message'=>'Deleted Successfully'));
	}

	public function actionUpdate($str, $id){
		$this->_comment->content = $str;
		$this->_comment->save();
		$this->renderSuccess(array('Comment_id'=>$this->_comment->id, 'Comment_id'=>$this->_comment->updated_at, 'Message'=>'Updated Successfully'));
	}
}