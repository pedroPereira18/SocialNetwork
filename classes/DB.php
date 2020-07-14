<?php 
class DB
{
    private static function connect()
    {
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=pap;charset=utf8','root','');
        $pdo -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    public static function query($query,$params=array())
    {
        $statement = self::connect() ->prepare($query);
        $statement ->execute($params);
       
        $data = $statement -> fetchALL();
        return $data;


    }
}	
class DB_update{

private static function connect()
	{
		$pdo = new PDO('mysql:host=127.0.0.1;dbname=pap;charset=utf8','root','');
		$pdo -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		return $pdo;
	}
public static function query_update($query_update,$paramss=array())
	{
		$statement = self::connect() ->prepare($query_update);
		$statement ->execute($paramss);
	 	$datas = $statement -> RowCount();
		return $datas;
	 
		
		
	}
}



?>