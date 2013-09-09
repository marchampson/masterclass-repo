<?php

session_start();

$config = require_once('../config.php');
require_once '../Upvote/Library/Front/Controller.php';

//require_once '../Comment.php';
//require_once '../User.php';
//require_once '../Story.php';
//require_once '../Index.php';

$framework = new Library\Front\Controller($config);
echo $framework->execute();
