<?php
class LikeController extends Controller {

    public function actionCreate() {
        if(isset($_POST['Like'])) {
            $existing_like = Like::model()->findByAttributes(array('user_id'=>$_POST['Like']['user_id'],'post_id'=>$_POST['Like']['post_id']));
            if(!$existing_like)
            {
                $like = Like::create($_POST['Like']);
                if(!$like->errors) {
                    $this->renderSuccess(array('post_id'=>$like->post_id,'user_id'=>$like->user_id));
                }
            }
            else {

                if($existing_like->status == 1){

                    $existing_like->deactivate();
                    $this->renderSuccess(array('success'=>"Like removed."));
                }
                else if($existing_like->status == 2){

                    $existing_like->activate();
                    $this->renderSuccess(array('success'=>"Liked."));
                }
                
            }

        }
        else {
            $this->renderError('ERROR.');
        }
    }
}