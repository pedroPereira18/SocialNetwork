<?php

class Comment 
{
	public static function createComment($commentbody,$postid,$userid)
	{
		
			if (strlen($commentbody) > 160 || strlen($commentbody) <1) 
				{
				  die('Incorrect Length'); 
				}
					
				if(!DB::query('SELECT id FROM posts WHERE id=:postid',array(':postid'=>$postid)))
				{
					echo "Post id invalido";
				}
				else
				{
					DB_update::query_update('INSERT INTO comments VALUES (\'\',:comment,:user_id,NOW(),:post_id)',array(':comment'=>$commentbody,':user_id'=>$userid,':post_id'=>$postid));
				}
	}

	public static function displayComments($postid)
	{
		$comments = DB::query('SELECT comments.comment, users.username FROM comments, users where post_id = :postid AND comments.user_id = users.id',array(':postid'=>$postid));
		foreach ($comments as $comment ) {
			echo $comment['comment']." --commented by ".$comment['username']."<hr/>";
		}
		
	}
}


?>