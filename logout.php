<?php
session_start();
require 'includes/config.php';
session_destroy();
header("Location: " . getBaseUrl() . "login.php");
exit;
