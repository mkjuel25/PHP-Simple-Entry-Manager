<?php
// save.php
$data_file = 'data.json';

$websites = file_exists($data_file) ? json_decode(file_get_contents($data_file), true) : [];
$id = $_POST['id'];

$websites[$id] = [
    'name' => $_POST['name'],
    'content' => $_POST['content'],
    'created' => isset($websites[$id]) ? $websites[$id]['created'] : date('Y-m-d H:i:s'),
    'updated' => date('Y-m-d H:i:s')
];

file_put_contents($data_file, json_encode($websites, JSON_PRETTY_PRINT));
