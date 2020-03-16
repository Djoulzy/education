<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class QueryManager
{
    private $catalog_list;
    private $queries_catalog;
    private $entityManager;
    private $conn;

    public function __construct(EntityManagerInterface $entityManager)
    {
        // if (file_exists('build/data/catalog.ini')) $this->queries_catalog = parse_ini_file('build/data/catalog.ini', true, INI_SCANNER_TYPED);
        $this->entityManager = $entityManager;
        $this->conn = $this->entityManager->getConnection();
        $this->queries_catalog = [];
        $this->catalog_list = [];
    }

    public function setCatalog($fileName)
    {
        if (isset($this->catalog_list[$fileName])) return true;

        if (file_exists($fileName)) {
            $this->queries_catalog = array_merge($this->queries_catalog, parse_ini_file($fileName, true, INI_SCANNER_TYPED));
            $this->catalog_list[$fileName] = true;
            return true;
        }
        else return false;
    }

    public function getQuery($name)
    {
        return $this->queries_catalog[$name]['query'];
    }

    public function getQueryLabel($name, $params = null)
    {
        $tmp = $this->queries_catalog[$name]['desc'];
        if (($params != null) && is_array($params)) {
            foreach($params as $key => $val)
                $tmp = str_replace('['.$key.']', $val, $tmp);
        }
        return $tmp;
    }

    public function execRawQuery($name, $params = null)
    {
        $RAW_QUERY = $this->queries_catalog[$name]['query'];
        if (($params != null) && is_array($params)) {
            foreach($params as $key => $val)
                $RAW_QUERY = str_replace('['.$key.']', $val, $RAW_QUERY);
        }

        $statement = $this->conn->prepare($RAW_QUERY);
        $statement->execute();

        $codeList = $statement->fetchAll();

        return $codeList;
    }

    public function execQuery($name, $params = null)
    {
        return json_encode(array_values($this->execRawQuery($name, $params)));
    }
}