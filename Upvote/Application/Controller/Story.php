<?php
namespace Upvote\Application\Controller;

use Upvote\Application\Model;

class Story {
	
	protected $config = array();
	protected $storyModel;
	protected $commentModel;
    
    public function __construct($config) {
		$this->config = $config;
		$this->storyModel = new Model\Story($config);
		$this->commentModel = new Model\Comment($config);
    }
    
    public function index() {
        if(!isset($_GET['id'])) {
            header("Location: /");
            exit;
        }
        
		$story = $this->storyModel->getStory($_GET['id']);
		
		if(is_null($story)) {
			header("Location: /");
			exit;
		}
		
		$comments = $this->commentModel->getComments($story['id']);
        $comment_count = $comments['count'];
        $comments = $comments['comments'];
        
		include(__DIR__ . '/../View/story/index.phtml');

        require_once 'layout.phtml';
        
    }
    
    public function create() {
        if(!isset($_SESSION['AUTHENTICATED'])) {
            header("Location: /user/login");
            exit;
        }
        
        $error = '';

        if(isset($_POST['create'])) {
            if(!isset($_POST['headline']) || !isset($_POST['url']) ||
               !filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL)) {
                $error = 'You did not fill in all the fields or the URL did not validate.';       
            } else {
				$id = $this->storyModel->createStory($_POST['headline'], $_POST['url'], $_SESSION['username']);
                header("Location: /story/?id=$id");
                exit;
            }
        }
        
        include(__DIR__ . '/../View/story/create.phtml');
        
        require_once 'layout.phtml';
    }
    
}