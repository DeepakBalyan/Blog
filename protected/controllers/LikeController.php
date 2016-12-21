<?php
class LikeController extends Controller {

 public function actionLike() {

   if(isset($_POST['Like'])) {

     $like = Like::create($_POST['Like']);
     if(!$like->errors) {
       $this->renderSuccess(array('post_id'=>$like->post_id,'user_id'=>$like->user_id));
     } 
     else {

       $this->renderError($this->getErrorMessageFromModelErrors($like));
     }

   } 
   else {

     $this->renderError('Please send post data!');
   }
 }

 public function actionCount($id){

   $counts = Like::model()->findAllByAttributes(array('post_id'=>$id));
   $users_data = array();
   foreach ($counts as $count) {

     $users_data[] = array('user_id'=>$count->user_id,'user_name'=>$count->user->name);
   }
   $this->renderSuccess(array(
    'status'=>'SUCCESS',
    'users_data'=>$users_data,
    'number_of_likes'=> count($counts)
    ));
 }
}
