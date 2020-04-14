<?php

require_once('inc/DBConn.class.php');
require_once('inc/DBError.class.php');

class PapersLight {

    public $user;

    private $_types;
    private $_dbhost;
    private $_dbname;
    private $_dbuser;
    private $_dbpass;

    public function __construct($types, $dbhost, $dbname, $dbuser, $dbpass) {
        $this->user = '';
        $this->_types = $types;
        $this->_dbhost = $dbhost;
        $this->_dbname = $dbname;
        $this->_dbuser = $dbuser;
        $this->_dbpass = $dbpass;

        if (isset($_COOKIE['pluser']) && isset($_COOKIE['plpass'])) {
            $this->adminLogin($_COOKIE['pluser'], $_COOKIE['plpass']);
        }
    }

    public function adminLogin($username, $password, $remember = true) {
        if ($username === $this->_dbuser && $password === $this->_dbpass) {
            if ($remember) {
                setcookie('pluser', $this->_dbuser, time() + 90 * 86400, '/');
                setcookie('plpass', $this->_dbpass, time() + 90 * 86400, '/');
            }
            $this->user = $this->_dbuser;
            $_SESSION['pl'] = serialize($this);
            return array('success' => 'admin-login-success');
        } else {
            return array('error' => 'admin-login-failed');
        }
    }

    public function getPapers() {
        try {
            $db = $this->getDatabase();
            $papers = array();
            foreach (array_keys($this->_types) as $type) {
                $result = $db->query('SELECT * FROM pl_'.$type);
                foreach ($result as $paper) {
                    $paper['type'] = $type;
                    array_push($papers, $paper);
                }
            }
            return $papers;
        } catch (DBError $e) {
            return array('error' => 'database-error');
        }
    }

    public function addPaper($type, $paper) {
        if ($this->user !== $this->_dbuser) {
            return array('error' => 'No database access permission');
        }

        try {
            $paper = (array) $paper;
            $columns = array_keys($paper);
            $values = array_map(function($column) {
                return ':'.$column;
            }, $columns);

            $qstr = 'INSERT INTO pl_'.$type;
            $qstr .= '('.join(',', $columns).')';
            $qstr .= ' VALUES('.join(',', $values).')';

            $params = array();
            for ($i = 0; $i < count($columns); ++$i) {
                array_push($params, array(
                    $values[$i],
                    $paper[$columns[$i]],
                    PDO::PARAM_STR
                ));
            }

            $db = $this->getDatabase();
            $db->query($qstr, $params);

            return array('success' => 'add-paper-success');
        } catch (DBError $e) {
            return array('error' => 'database-error');
        }
    }

    public function updatePaper($origType, $newType, $paperId, $paper) {
        if ($this->user !== $this->_dbuser) {
            return array('error' => 'No database access permission');
        }

        try {
            if ($origType === $newType) {
                $paper = (array) $paper;
                $columns = array_keys($paper);
                $values = array_map(function($column) {
                    return $column.' = :'.$column;
                }, $columns);

                $qstr = 'UPDATE pl_'.$newType;
                $qstr .= ' SET '.join(',', $values);
                $qstr .= ' WHERE paper_id = :paper_id';

                $params = array();
                for ($i = 0; $i < count($columns); ++$i) {
                    array_push($params, array(
                        ':'.$columns[$i],
                        $paper[$columns[$i]],
                        PDO::PARAM_STR
                    ));
                }
                array_push($params, array(':paper_id', (int) $paperId, PDO::PARAM_INT));

                $db = $this->getDatabase();
                $db->query($qstr, $params);

                return array('success' => 'update-paper-success');
            } else {
                $result = $this->removePaper($origType, $paperId);
                if (isset($result['error'])) return $result;

                $result = $this->addPaper($newType, $paper);
                if (isset($result['error'])) return $result;

                return array('success' => 'update-paper-success');
            }
        } catch (DBError $e) {
            return array('error' => 'database-error');
        }
    }

    public function removePaper($type, $paperId) {
        if ($this->user !== $this->_dbuser) {
            return array('error' => 'No database access permission');
        }

        try {
            $qstr = 'DELETE FROM pl_'.$type.' WHERE paper_id = :paper_id';
            $params = array(array(':paper_id', (int) $paperId, PDO::PARAM_INT));

            $db = $this->getDatabase();
            $db->query($qstr, $params);

            return array('success' => 'remove-paper-success');
        } catch (DBError $e) {
            return array('error' => 'database-error');
        }
    }

    private function getDatabase() {
        return new DBConn('mysql', $this->_dbname, $this->_dbuser, $this->_dbpass, $this->_dbhost);
    }
}
