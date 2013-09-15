<?php

namespace Upvote\Application\Model;

class User {
	
	public function __construct(array $config = array()) {
		$dbconfig = $config['database'];
        $dsn = 'mysql:host=' . $dbconfig['host'] . ';dbname=' . $dbconfig['name'];
        $this->db = new \PDO($dsn, $dbconfig['user'], $dbconfig['pass']);
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}
	
	public function checkUsernameExists($username) {
		$check_sql = 'SELECT * FROM user WHERE username = ?';
        $check_stmt = $this->db->prepare($check_sql);
        $check_stmt->execute(array($username));
		return $check_stmt->rowCount();
	}
	
	public function createUser($username, $email, $password) {
		$params = array(
            $username,
            $email,
            md5($username . $password),
        );

        $sql = 'INSERT INTO user (username, email, password) VALUES (?, ?, ?)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
		return true;
	}
	
	public function updatePassword($password, $username) {
		$sql = 'UPDATE user SET password = ? WHERE username = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
           md5($username . $password), // THIS IS NOT SECURE. 
           $username,
        ));
		return true;
	}
	
	public function getUser($username) {
		$dsql = 'SELECT * FROM user WHERE username = ?';
        $stmt = $this->db->prepare($dsql);
        $stmt->execute(array($username));
        return $stmt->fetch(\PDO::FETCH_ASSOC);
	}
	
	public function login($username, $password) {
		$sql = 'SELECT * FROM user WHERE username = ? AND password = ? LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($username, $password));
		if($stmt->rowCount() > 0) {
           $data = $stmt->fetch(\PDO::FETCH_ASSOC); 
           session_regenerate_id();
           $_SESSION['username'] = $data['username'];
           $_SESSION['AUTHENTICATED'] = true;
		   return true;
        } else {
			return false;
		}
	}
}