<?php
namespace Upvote\Application\Controller;

use Upvote\Application\Model;

class Comment {

	protected $config = array();
	protected $commentModel;

    public function __construct($config) {
        $this->config = $config;
		$this->commentModel = new Model\Comment($config);
    }
    
    public function create() {
        if(!isset($_SESSION['AUTHENTICATED'])) {
            die('not auth');
            header("Location: /");
            exit;
        }
        
        $this->commentModel->createComment($_POST['story_id'], $_SESSION['username'], $_POST['comment']);

        header("Location: /story/?id=" . $_POST['story_id']);
    }
    
}