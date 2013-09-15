<?php

session_start();
date_default_timezone_set('Europe/London');

$config = require_once('../config.php');
set_include_path(get_include_path() . ":" . realpath('..'));
require_once 'Upvote/Library/Front/Controller.php';

$framework = new Upvote\Library\Front\Controller($config);
echo $framework->execute();
