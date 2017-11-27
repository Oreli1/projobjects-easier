<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\Webforce3\DB;

use Classes\Webforce3\Config\Config;

/**
 * Description of Training
 *
 * @author Etudiant
 */
class Training extends DbObject {
    /** @var string */
    private $name;
    
    public function __construct($id = 0, $name = '',$inserted = '') {
        
        $this->name = $name;
        
        parent::__construct($id, $inserted);
    }

    public static function get($id) {
		$sql = '
			SELECT tra_id, tra_name
			FROM training
			WHERE tra_id = :id
			ORDER BY cou_name ASC
		';
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		$stmt->bindValue(':id', $id, \PDO::PARAM_INT);

		if ($stmt->execute() === false) {
			throw new InvalidSqlQueryException($sql, $stmt);
		}
		else {
			$row = $stmt->fetch(\PDO::FETCH_ASSOC);
			if (!empty($row)) {
				$currentObject = new Training(
					$row['tra_id'],
                                        $row['tra_name']			
				);
				return $currentObject;
			}
		}

		return false;
	}
    
    public static function deleteById($id) {
		$sql = '
			DELETE FROM training WHERE tra_id = :id
		';
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		$stmt->bindValue(':id', $id, \PDO::PARAM_INT);

		if ($stmt->execute() === false) {
			print_r($stmt->errorInfo());
		}
		else {
			return true;
		}
		return false;
	}

    public static function getAll() {
		$returnList = array();
                $sql = '
			SELECT tra_id, tra_name
			FROM training
			WHERE tra_id > 0
			ORDER BY cou_name ASC
		';
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		if ($stmt->execute() === false) {
			throw new InvalidSqlQueryException($sql, $stmt);
		}
		else {
			$allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			foreach ($allDatas as $row) {
				$currentObject = new Training(
					$row['tra_id'],
                                        $row['tra_name']
				);
				$returnList[] = $currentObject;
			}
		}

		return $returnList;
	}

    public static function getAllForSelect() {
		$returnList = array();

		$sql = '
			SELECT tra_id, tra_name
			FROM training
			WHERE tra_id > 0
			ORDER BY cou_name ASC
		';
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		if ($stmt->execute() === false) {
			print_r($stmt->errorInfo());
		}
		else {
			$allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			foreach ($allDatas as $row) {
				$returnList[$row['tra_id']] = $row['tra_name'];
			}
		}

		return $returnList;
	}

    public function saveDB() {
                  if ($this->id > 0){
			$sql = '
				UPDATE training
				SET tra_name = :name,
				WHERE tra_id = :id
			';
			$stmt = Config::getInstance()->getPDO()->prepare($sql);
			$stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
			$stmt->bindValue(':name', $this->name);
			

			if ($stmt->execute() === false) {
				throw new InvalidSqlQueryException($sql, $stmt);
			}
			else {
				return true;
			}
		}
		else {
			$sql = '
				INSERT INTO training (tra_name)
				VALUES (:name)
			';
			$stmt = Config::getInstance()->getPDO()->prepare($sql);
			$stmt->bindValue(':name', $this->name, \PDO::PARAM_INT);
                        
			if ($stmt->execute() === false) {
				throw new InvalidSqlQueryException($sql, $stmt);
			}
			else {
				$this->id = Config::getInstance()->getPDO()->lastInsertId();
				return true;
			}
		}

		return false;
	}

}
