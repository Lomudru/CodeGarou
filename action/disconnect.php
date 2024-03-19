<?php
require "../utils/common.php";

session_destroy();
header("Location: ". PROJECT_FOLDER . "index.php");