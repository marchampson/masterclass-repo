<?php

namespace Upvote\Application\Model;

class Comment {
	
	public function __construct(array $config = array()) {
		$dbconfig = $config['database'];
        $dsn = 'mysql:host=' . $dbconfig['host'] . ';dbname=' . $dbconfig['name'];
        $this->db = new \PDO($dsn, $dbconfig['user'], $dbconfig['pass']);
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}
	
	public function createComment($story_id, $username, $comment) {
		$sql = 'INSERT INTO comment (created_by, created_on, story_id, comment) VALUES (?, NOW(), ?, ?)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
            $username,
            $story_id,
            filter_var($comment, FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        ));
	}
	
	public function getComments($story_id) {
		$comment_sql = 'SELECT * FROM comment WHERE story_id = ?';
        $comment_stmt = $this->db->prepare($comment_sql);
        $comment_stmt->execute(array($story_id));
        $comment_count = $comment_stmt->rowCount();
        $comments = $comment_stmt->fetchAll(\PDO::FETCH_ASSOC);
		return array('count' => $comment_count, 'comments' => $comments);
	}
}