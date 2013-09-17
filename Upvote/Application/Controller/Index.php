<?php
namespace Upvote\Application\Controller;

use Upvote\Application\Model;

class Index {
    
	protected $config;
	protected $storyModel;
	protected $commentModel;
    
    public function __construct($config) {
        $this->config = $config;
		$this->storyModel = new Model\Story($config);
		$this->commentModel = new Model\Comment($config);
    }
    
    public function index() {
        
        $stories = $this->storyModel->fetchAll();

		$storiesArray = array();
		foreach($stories as $story) {
			$comments = $this->commentModel->getComments($story['id']);
			$storiesArray[] = array('data' => $story, 'comments_count' => $comments['count']);
		}

		include(__DIR__ . '/../View/index/index.phtml');
        
        require 'layout.phtml';
    }
}