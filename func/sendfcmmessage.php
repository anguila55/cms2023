<?
	function sendFCMMessage($data,$target){
	   //FCM API end-point
	   $url = 'https://fcm.googleapis.com/fcm/send';
	   $server_key = 'AAAAwGIljjQ:APA91bEQuFSVJ3LeyHysvNY7wCBSWmH8XGMpl8ZKqwdF6xkYWBtrKBYyFuU2rlx6sBT3NePUJgnAESUpvKABpEUgT3zTvgEgHsXoETNYyQC8Elsg28r6D2_3rTNMrHU2qBP1E7767i_8';

		$data['body'] = $data['text'];

	   $fields = array();
	   $fields['notification'] = $data;
	   if(is_array($target)){   		
	      	$fields['registration_ids'] = $target;
	   }else{
		  $fields['to'] = $target;
	   }
	   logerror(json_encode($fields));
		//header with content_type api key
	   $headers = array(
			'Content-Type:application/json',
			'Authorization:key='.$server_key
	   );
	   //CURL request to route notification to FCM connection server (provided by Google)			
	   $ch = curl_init();
	   curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,300);
	   curl_setopt($ch, CURLOPT_URL, $url);
	   curl_setopt($ch, CURLOPT_POST, true);
	   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	   curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
	   $result = curl_exec($ch);
	   if ($result === FALSE) {
			//die('Oops! FCM Send Error: ' . curl_error($ch));
	   		//logerror('ERROR FCM: '.curl_error($ch));
	   }
	   //logerror('FRM RESULT: '.$result);
	   curl_close($ch);  
		
	}
	
?>	
