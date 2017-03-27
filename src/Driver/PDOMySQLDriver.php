<?php

namespace SQLBuilder\Driver;

use PDO;

/**
 * Class PDOMySQLDriver
 *
 * @package SQLBuilder\Driver
 *
 * @author  Yo-An Lin (c9s) <cornelius.howl@gmail.com>
 * @author  Aleksey Ilyenko <assada.ua@gmail.com>
 */
class PDOMySQLDriver extends MySQLDriver
{
    /**
     * @var \PDO
     */
    public $pdo;

    /**
     * PDOMySQLDriver constructor.
     *
     * @param \PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param $str
     *
     * @return string
     */
    public function quote($str)
    {
        return $this->pdo->quote($str);
    }

    /**
     * @return \PDO
     */
    public function getConnection()
    {
        return $this->pdo;
    }

    /**
     * @return mixed
     */
    public function getDriverName()
    {
        return $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
    }
}
