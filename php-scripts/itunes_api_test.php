<?php
require('itunes_functions.php');

$keywords = $_GET['keywords'];

print_r(searchItunesSong($keywords));
?>