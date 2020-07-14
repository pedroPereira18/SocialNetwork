<?php 

class Post
{
	public static function createPost($postbody,$loggedInUserId,$profileUserID)
	{
		
			if (strlen($postbody) > 160 || strlen($postbody) <1) 
				{
				  die('Incorrect Length'); 
				}
				$topics = self::Find_topics($postbody);
					if($loggedInUserId == $profileUserID)
					{
						if (count(Notify::Createnotify($postbody))!=0) 
						{
							foreach (Notify::Createnotify($postbody) as $key => $n ) 
							{
								$s = $loggedInUserId;
								$r = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$key))[0]['id'];

								if ($r != 0) {
									DB_update::query_update('INSERT INTO notifications VALUES (\'\', :type, :receiver,:sender,:extra)',array(':type'=>$n["type"],':receiver'=>$r,':sender'=>$s,':extra'=>$n["extra"]));
								}
									
							}
						}
						DB_update::query_update('INSERT INTO posts VALUES (\'\',:postbody,NOW(),0,:userid,\'\',:topics)', array(':postbody'=>$postbody,':userid' => $profileUserID,':topics'=>$topics));
					}
					else{
						die('Incorrect user');
					}
					
	}

	public static function createIMGPost($postbody,$loggedInUserId,$profileUserID)
	{
		
			if (strlen($postbody) > 160 ) 
				{
				  die('Incorrect Length'); 
				}
				$topics =self::Find_topics($postbody);
					if($loggedInUserId == $profileUserID)
					{
						if (count(Notify::Createnotify($postbody))!=0) 
						{
							foreach (Notify::Createnotify($postbody) as $key => $n ) 
							{
								$s = $loggedInUserId;
								$r = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$key))[0]['id'];

								if ($r != 0) {
									DB_update::query_update('INSERT INTO notifications VALUES (\'\', :type, :receiver,:sender,:extra)',array(':type'=>$n["type"],':receiver'=>$r,':sender'=>$s,':extra'=>$n["extra"]));
								}
									
							}
						}
						DB_update::query_update('INSERT INTO posts VALUES (\'\',:postbody,NOW(),0,:userid,\'\',topics)', array(':postbody'=>$postbody,':userid' => $profileUserID));
						$postid = DB::query('SELECT id FROM posts WHERE user_id=:userid ORDER BY id DESC LIMIT 1;',array(':userid'=>$loggedInUserId))[0]['id'];
						return $postid;
					}
					else{
						die('Incorrect user');
					}
	}

	

	public static function LikePost($postid,$likerid)
	{
		if (!DB::query('SELECT user_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=> $postid,':userid' => $likerid))) 
					   {
						
						
						DB_update::query_update('UPDATE posts SET likes=likes+1 WHERE id=:postid',array(':postid'=> $postid ));
						DB_update::query_update('INSERT INTO post_likes VALUES (\'\',:postid,:userid)', array(':postid'=>$postid,':userid' => $likerid));
						Notify::Createnotify("",$postid);

						}
						else
						{
						DB_update::query_update('UPDATE posts SET likes=likes-1 WHERE id=:postid',array(':postid'=> $postid ));
					    DB_update::query_update('DELETE FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$postid,':userid' => $likerid));
						}
	}
	public static function Find_topics($text)
		{
			$text = explode(" ", $text);
			
			$topics = "";

			foreach ($text as $word ) 
			{
				
				if (substr($word, 0,1)=="#") 
				{
						$topics	.= substr($word, 1).",";
				}
							
			}					
				return $topics;
		}


	public static function link_add($text)
		{
			$text = explode(" ", $text);
			$newstring ="";

			foreach ($text as $word ) {
				if (substr($word, 0,1)=="@") {
					$newstring .="<a href='profile.php?u=".substr($word,1)."'> ".htmlspecialchars($word)." </a>";
				}
				else if (substr($word, 0,1)=="#") {
					$newstring .="<a  href='topics.php?topic=".substr($word,1)."'> ".htmlspecialchars($word)." </a>";
				}
				else
				{
				$newstring .=htmlspecialchars($word)." ";
				}
				
				
			}
			
				return ($newstring);
		}


	public static function DisplayPosts($userid,$username,$loggedInUserId )
		{

				$dbposts = DB::query('SELECT * FROM posts WHERE user_id=:userid ORDER BY id DESC',array(':userid'=>$userid));
				$posts = "";
				foreach ($dbposts as $p ) 
				{
					if(!DB::query('SELECT post_id FROM post_likes WHERE post_id=:postid AND user_id=:userid',array(':postid'=>$p['id'],':userid' => $loggedInUserId)))
					{
					$posts .= "<img src='".$p['postimg']."'>".self::link_add($p['body'])."
					<form action='profile.php?u=$username&postid=".$p['id']."' method='POST'>
					<input type='submit' name='like' value='like'>
					<span>".$p['likes']." likes</span>
					";
					if ($userid == $loggedInUserId ) {
						$posts .="<input type='submit' name='deletepost' value='x'/>";
					}
					$posts .= "
					</form><hr/></br/>";

				     }
					else
					{
					$posts .= "<img src='".$p['postimg']."'>".htmlspecialchars(self::link_add($p['body']))."
					<form action='profile.php?u=$username&postid=".$p['id']."' method='POST'>
					<input type='submit' name='unlike' value='unlike'>
					<span>".$p['likes']." likes</span>
						";
						
					if ($userid == $loggedInUserId ) {
						$posts .="<input type='submit' name='deletepost' value='x'/>";
					}
					$posts .= "
					</form><hr/></br/>";
					}
					 

			    

	    }   return $posts;

	}
	
}

?>