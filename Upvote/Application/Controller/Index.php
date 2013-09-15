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
        
        $content = '<ol>';
        
        foreach($stories as $story) {
			$comments = $this->commentModel->getComments($story['id']);
            $content .= '
                <li>
                <a class="headline" href="' . $story['url'] . '">' . $story['headline'] . '</a><br />
                <span class="details">' . $story['created_by'] . ' | <a href="/story/?id=' . $story['id'] . '">' . $comments['count'] . ' Comments</a> | 
                ' . date('n/j/Y g:i a', strtotime($story['created_on'])) . '</span>
                </li>
            ';
        }
        
        $content .= '</ol>';
        
        require 'layout.phtml';
    }
}