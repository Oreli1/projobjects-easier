<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes\Webforce3\DB;

use Classes\Webforce3\Config\Config;

Class Location extends DbObject {
    
    /** @var String */
    private $name;
    /** @var Country */
    private $country;
    
    public function __construct($id = 0, $name = '',  $inserted = '', $country = NULL) {
        if (empty($country)) {
			$this->country = new country();
		}
		else {
			$this->country = $country;
		}
         $this->name = $name;
        parent::__construct($id, $inserted);
    }
    public static function get($id) {
		$sql = '
			SELECT loc_id, loc_name, country_cou_id
			FROM location
			WHERE loc_id = :id
			ORDER BY cit_name ASC
		';
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		$stmt->bindValue(':id', $id, \PDO::PARAM_INT);

		if ($stmt->execute() === false) {
			throw new InvalidSqlQueryException($sql, $stmt);
		}
		else {
			$row = $stmt->fetch(\PDO::FETCH_ASSOC);
			if (!empty($row)) {
				$currentObject = new Location(
					$row['cit_id'],
                                        $row['cit_name'],
					new Country($row['country_cou_id'])			
				);
				return $currentObject;
			}
		}

		return false;
	}


    public static function deleteById($id) {
		$sql = '
			DELETE FROM location WHERE loc_id = :id
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
			SELECT loc_id, loc_name, country_cou_id
			FROM location
			WHERE loc_id = :id
			ORDER BY cit_name ASC
		';
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		if ($stmt->execute() === false) {
			throw new InvalidSqlQueryException($sql, $stmt);
		}
		else {
			$allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			foreach ($allDatas as $row) {
				$currentObject = new City(
					$row['loc_id'],
                                        $row['loc_name'],
					new Country($row['country_cou_id'])
				);
				$returnList[] = $currentObject;
			}
		}

		return $returnList;
	}

    public static function getAllForSelect() {
		$returnList = array();

		$sql = '
			SELECT loc_id, loc_name, country_cou_id
			FROM location
			WHERE loc_id > 0
			ORDER BY cit_name ASC
		';
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		if ($stmt->execute() === false) {
			throw new InvalidSqlQueryException($sql, $stmt);
		}
		else {
			$allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			foreach ($allDatas as $row) {
				$returnList[$row['loc_id']] = $row['loc_name'].' '.$row['country_cou_id'];
			}
		}

		return $returnList;
	}

    public function saveDB() {
                  if ($this->id > 0){
			$sql = '
				UPDATE location
				SET loc_name = :name,
				country_cou_id = :couId,
				WHERE loc_id = :id
			';
			$stmt = Config::getInstance()->getPDO()->prepare($sql);
			$stmt->bindValue(':id', $this->id, \PDO::PARAM_INT);
			$stmt->bindValue(':name', $this->name);
			$stmt->bindValue(':country_cou_id', $this->country->id, \PDO::PARAM_INT);
			

			if ($stmt->execute() === false) {
				throw new InvalidSqlQueryException($sql, $stmt);
			}
			else {
				return true;
			}
		}
		else {
			$sql = '
				INSERT INTO location (loc_name, country_cou_id)
				VALUES (:name, :couId)
			';
			$stmt = Config::getInstance()->getPDO()->prepare($sql);
			$stmt->bindValue(':name', $this->name, \PDO::PARAM_INT);
			$stmt->bindValue(':couId', $this->country->id, \PDO::PARAM_INT);
			
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



