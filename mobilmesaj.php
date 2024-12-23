<?php
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
