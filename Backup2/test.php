<?php
$conn_string = ("host=localhost port=5432 dbname=pustekbang_v2 user=postgres password=s3m4ng4t");
$dbconn4 = pg_connect($conn_string);
print_r($dbconn4);
echo "test";
?> 
