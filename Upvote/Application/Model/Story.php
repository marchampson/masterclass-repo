<?php 

namespace Upvote\Application\Model;

class Story {
	
	public function __construct(array $config = array()) {
		$dbconfig = $config['database'];
        $dsn = 'mysql:host=' . $dbconfig['host'] . ';dbname=' . $dbconfig['name'];
        $this->db = new \PDO($dsn, $dbconfig['user'], $dbconfig['pass']);
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}
	
	public function fetchAll() {
		$sql = 'SELECT * FROM story ORDER BY created_on DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}
	
	public function getStory($story_id) {
		$story_sql = 'SELECT * FROM story WHERE id = ?';
        $story_stmt = $this->db->prepare($story_sql);
        $story_stmt->execute(array($story_id));
        if($story_stmt->rowCount() < 1) {
            $story = null;
        } else {
			$story = $story_stmt->fetch(\PDO::FETCH_ASSOC);
		}
        
		return $story;
	}
	
	public function createStory($headline, $url, $username) {
		$sql = 'INSERT INTO story (headline, url, created_by, created_on) VALUES (?, ?, ?, NOW())';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
           filter_var($headline, FILTER_SANITIZE_FULL_SPECIAL_CHARS),
           filter_var($url, FILTER_SANITIZE_FULL_SPECIAL_CHARS),
           $username,
        ));
        
        $id = $this->db->lastInsertId();
        
	}
}