<?php
include("src/RtcTokenBuilder.php");

function agoraGenToken($channel,$useruid){

	$appID = "7e5c6f3f98b44f62a6c2ab5341fc5c70";
	$appCertificate = "316987dfce3d480b8b821e8d0c813a00";

	$channelName = $channel;
	$uid = $useruid;
	$uidStr = "$useruid";

	$role = RtcTokenBuilder::RoleAttendee;
	$expireTimeInSeconds = 3600; //1h
	$currentTimestamp = (new DateTime("now", new DateTimeZone('UTC')))->getTimestamp();
	$privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;

	$token = RtcTokenBuilder::buildTokenWithUid($appID, $appCertificate, $channelName, $uid, $role, $privilegeExpiredTs);
	//$token = RtcTokenBuilder::buildTokenWithUserAccount($appID, $appCertificate, $channelName, $uidStr, $role, $privilegeExpiredTs);
	
	return $token;
}
?>
