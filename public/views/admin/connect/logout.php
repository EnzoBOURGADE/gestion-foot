<?php
require 'session.php';
session_destroy();
header("Location: ../connect/login.php");
exit;
