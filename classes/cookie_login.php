<?php
class Login
{
    public static function isloggedIn()
    {
       if (isset($_COOKIE['SNID'])) 
      { 
            $user = DB::query('SELECT  user_id FROM tokens WHERE token=:token', 
        array(':token' =>sha1($_COOKIE['SNID'])));

		if ( isset($user[0]['user_id'])) {
    			$userid = $user[0]['user_id'];
    			         
      }
        return $userid;
    }
}
}



?>