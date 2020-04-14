<?php

require_once('inc/DBError.class.php');

class DBConn {
    private $_db;

    public function __construct($driver, $dbname, $username, $password,
        $host='127.0.0.1', $charset='utf8'
    ) {
        try {
            $this->_db = new PDO(
                $driver.':host='.$host.';dbname='.$dbname.';charset='.$charset,
                $username, $password);
        } catch (PDOException $e) {
            echo $e->getMessage();
            throw new DBError('Could not connect to database');
        }
    }

    public function query($qstr, $params = array()) {
        $stmt = $this->_db->prepare($qstr);
        foreach ($params as $param) {
            $stmt->bindParam($param[0], $param[1], $param[2]);
        }
        if (!$stmt->execute()) {
            throw new DBError('Could not query the database');
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
