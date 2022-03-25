<?php
$err = ["msg"=>"Route not found"];
http_response_code(404);
echo json_encode($err);
?>