<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
$tokenbaslik=$_POST["08507063407"];
$tokenmesaj=$_POST["fa129487108492b31a19523e"];
$tokenid=$_POST["bb97756508ea24dd0397848c"];

$ch = curl_init("https://fcm.googleapis.com/fcm/send");
$header=array('Content-Type: application/json',
"Authorization: key=AAAA8p2LEd8:APA91bERg7Km7H1uPP0R9p_BiRjXnLQRJFF9fLHTjN1QUEQXqTfX6pqRzw_5cyIivrevuaYgdRTkXkOuyPzpE61LJ7IzaaqRGHHeJYcyV4JrdjJ3rHWidBiRBt3eaatTQOOOYcy61qAC");
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 ); 
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"notification\": {\"sound\": \"default\",    \"title\": \"$tokenbaslik\",    \"body\": \"$tokenmesaj\"  },    \"to\" : \"$tokenid\"}");
curl_exec($ch);
curl_close($ch);
?>


</body>
</html>