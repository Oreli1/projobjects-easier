<?php

namespace Classes\Webforce3\DB;

use Classes\Webforce3\Config\Config;

class Session extends DbObject {
    
        /** @var Location */
        protected $location;
        /** @var Training */
        protected $training;
        /** @var DateTime */
        protected $startDate;
        /** @var DateTime */
        protected $endDate;
        /** @var Integer */
        protected $number;
        
        public function __construct($id = 0, $location = NULL, $training = NULL,  $startDate = NULL, $endDate = NULL, $number = '',  $inserted = '') {
            
            if (empty($location)) {
			$this->location = new location();
		}
		else {
			$this->location = $location;
		}
            if (empty($training)) {
			$this->training = new training();
		}
		else {
			$this->training = $training;
		}
            $this->startDate = $startDate;
            $this->endDate = $endDate;
            $this->number = $number;
            
            parent::__construct($id, $inserted);
        }
                
          
        public static function get($id){
                $sql = '
			SELECT ses_id, ses_start_date, ses_end_date, ses_member, location_loc_id, training_tra_id
			FROM session
			WHERE ses_id = :id
			ORDER BY ses_start_date ASC
		';
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		$stmt->bindValue(':id', $id, \PDO::PARAM_INT);

		if ($stmt->execute() === false) {
			throw new InvalidSqlQueryException($sql, $stmt);
		}
		else {
			$row = $stmt->fetch(\PDO::FETCH_ASSOC);
			if (!empty($row)) {
				$currentObject = new Session(
					$row['ses_id'],
                                        $row['ses_start_date'],
                                        $row['ses_end_date'],
                                        $row['ses_member'],
                                        new Location($row['location_loc_id']),
					new Traininf($row['training_tra_id'])
				);
				return $currentObject;
			}
		}

		return false;
	}

	public static function getAll()  {
		$returnList = array();
                $sql = '
			SELECT ses_id, ses_start_date, ses_end_date, ses_member, location_loc_id, training_tra_id
			FROM session
			WHERE ses_id > 0
		';
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		if ($stmt->execute() === false) {
			throw new InvalidSqlQueryException($sql, $stmt);
		}
		else {
			$allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			foreach ($allDatas as $row) {
				$currentObject = new Session(
					$row['ses_id'],
                                        $row['ses_start_date'],
                                        $row['ses_end_date'],
                                        $row['ses_member'],
                                        new Location($row['location_loc_id']),
					new Traininf($row['training_tra_id'])
				);
				$returnList[] = $currentObject;
			}
		}

		return $returnList;
	}

	/**
	 * @return array
	 */
	public static function getAllForSelect() {
		$returnList = array();

		$sql = '
			SELECT ses_id, tra_name, ses_start_date, ses_end_date, loc_name
			FROM session
			LEFT OUTER JOIN training ON training.tra_id = session.training_tra_id
			LEFT OUTER JOIN location ON location.loc_id = session.location_loc_id
			WHERE ses_id > 0
			ORDER BY ses_start_date ASC
		';
		$stmt = Config::getInstance()->getPDO()->prepare($sql);
		if ($stmt->execute() === false) {
			print_r($stmt->errorInfo());
		}
		else {
			$allDatas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
			foreach ($allDatas as $row) {
				$returnList[$row['ses_id']] = '['.$row['ses_start_date'].' > '.$row['ses_end_date'].'] '.$row['tra_name'].' - '.$row['loc_name'];
			}
		}

		return $returnList;
	}

	/**
	 * @param int $sessionId
	 * @return DbObject[]
	 */
	/* @return bool
	 */
	public function saveDB() {
		// TODO: Implement saveDB() method.
	}

	/**
	 * @param int $id
	 * @return bool
	 */
	public static function deleteById($id) {
		// TODO: Implement deleteById() method.
	}

}