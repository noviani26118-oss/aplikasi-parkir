<?php
require_once 'functions/helpers.php';

session_destroy();
redirect('login.php');
?>
