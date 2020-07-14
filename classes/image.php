<?php 

class Image
{
	
	public static function uploadImage($formname,$query, $params)
	{
		$image = base64_encode(file_get_contents($_FILES[$formname]['tmp_name']));
	$options = array('http'=>array(
		'method'=>'POST',
		'header'=>"Authorization: Bearer 6f45d9527d8b7879e8ff77f3f1916a0808aee971\n".
		"Content-Type: application/x-www-form-urlencoded",
		'content'=>$image));
	
	$imgurURL ="https://api.imgur.com/3/image";
	$context = stream_context_create($options);
	if ($_FILES[$formname]['size']> 10240000) 
	{
		die('image too big, must be 10MB or less!');
	}
	$response = file_get_contents($imgurURL, false, $context);
	$response = json_decode($response);
	/*echo '<pre>';
	print_r($response);
	echo '</pre>';
	echo $response->data->link;
	*/
	$preparams = array($formname=>$response->data->link);
	$params = $preparams + $params;
	DB_update::query_update($query, $params);
	}
}


?>
