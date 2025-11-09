<?php
require 'session.php';
session_destroy();
header("Location: ../player/index.php");
exit;
