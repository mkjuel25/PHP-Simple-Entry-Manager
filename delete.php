<?php
// delete.php
$data_file = 'data.json';

$websites = json_decode(file_get_contents($data_file), true);
unset($websites[$_POST['id']]);
file_put_contents($data_file, json_encode($websites, JSON_PRETTY_PRINT));
