<?php
class CommentController extends Controller {

   public function actionCreate() {

       if(isset($_POST['Comment'])) {

           $comment = Comment::create($_POST['Comment']);
           if(!$comment-> errors) {
               $this->renderSuccess(array('post_id'=>$comment-> post_id,'content'=>$comment-> content,'user_id'=>$comment-> user_id));
           }
           else {
               $this->renderError($this->getErrorMessageFromModelErrors($comment));
           }
       } 
       else {

           $this-> renderError('Please send post data!');
       }
   }


   public function actionCount($id){

       $counts = Comment::model()-> findAllByAttributes(array('post_id'=> $id));
       $users_data = array();
       $number_of_comments = 0;
       foreach ($counts as $count) {

        if($count->status==1){
            $number_of_comments++;
            $users_data[] = array('user_id'=>$count-> user_id,'user_name'=>$count-> user-> name);
        }
    }
    $this->renderSuccess(array('status'=>'SUCCESS',

       'users_data'=> $users_data, 'Number_of_comments'=> $number_of_comments
       ));
}


public function actionTopComments($id){

   $comments = Comment::model()->findAll(array(
    'condition'=> "post_id = :post_id",
    'params'=> array('post_id'=>$id),
    'order'=> 'created_at DESC',
    'limit'=> 5
    ));

   $comments_data = array();

   foreach($comments as $comment){

    if($comment->status==1){
       $comments_data[] = array('user_name'=>$comment-> user-> name, 'content'=>$comment-> content);
   }
}

$this->renderSuccess(array('status'=>'SUCCESS', 'Comments_information'=> $comments_data));
}

public function actionDelete($id){

    $comment = Comment::model()-> findByPk($id);
    $comment-> status = 2;
    $comment-> save();
}
public function actionRestore($id){

    $comment = Comment::model()-> findByPk($id);
    $comment-> status = 1;
    $comment-> save();
}


public function actionUpdate($str, $id){

   $comment = Comment::model()->findByPk($id);
   if($comment->status ==1){

    $comment->content = $str;
    $comment->save();
}


}



}