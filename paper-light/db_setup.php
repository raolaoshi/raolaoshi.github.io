<?php

require_once('inc/common.php');

echo <<<EOD
CREATE DATABASE IF NOT EXISTS {$DBNAME} DEFAULT CHARSET UTF8 COLLATE UTF8_GENERAL_CI;
CREATE USER "{$DBUSER}"@"{$DBHOST}" IDENTIFIED BY "{$DBPASS}";
GRANT ALL ON {$DBNAME}.* TO "{$DBUSER}";

USE {$DBNAME};

CREATE TABLE IF NOT EXISTS pl_tag(
    tag_id INT AUTO_INCREMENT PRIMARY KEY,
    tag_name VARCHAR(64) NOT NULL
);

EOD;

foreach ($TYPES as $type => $attr)
{
    echo "CREATE TABLE IF NOT EXISTS pl_{$type}(\n";
    echo "    paper_id INT AUTO_INCREMENT PRIMARY KEY,\n";
    $cols = array();
    foreach ($attr['required'] as $a) {
        array_push($cols, "    {$a} TEXT NOT NULL");
    }
    foreach ($attr['optional'] as $a) {
        array_push($cols, "    {$a} TEXT");
    }
    echo join(",\n", $cols);
    echo "\n);\n";

    echo "CREATE TABLE IF NOT EXISTS pl_{$type}2tag(\n";
    echo "    paper_id INT NOT NULL, INDEX(paper_id),\n";
    echo "    FOREIGN KEY(paper_id) REFERENCES pl_{$type}(paper_id) ON DELETE CASCADE,\n";
    echo "    tag_id INT NOT NULL, INDEX(tag_id),\n";
    echo "    FOREIGN KEY(tag_id) REFERENCES pl_tag(tag_id) ON DELETE CASCADE,\n";
    echo "    UNIQUE KEY(paper_id, tag_id)\n";
    echo ");\n";
}
