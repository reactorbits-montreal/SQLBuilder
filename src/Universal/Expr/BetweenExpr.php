<?php

namespace SQLBuilder\Universal\Expr;

use SQLBuilder\ArgumentArray;
use SQLBuilder\Driver\BaseDriver;
use SQLBuilder\ToSqlInterface;

/**
 * Class BetweenExpr
 *
 * http://dev.mysql.com/doc/refman/5.0/en/comparison-operators.html#operator_between.
 *
 * @package SQLBuilder\Universal\Expr
 *
 * @author  Yo-An Lin (c9s) <cornelius.howl@gmail.com>
 * @author  Aleksey Ilyenko <assada.ua@gmail.com>
 */
class BetweenExpr implements ToSqlInterface
{
    /**
     * @var string
     */
    public $exprStr;

    public $min;

    public $max;

    /**
     * BetweenExpr constructor.
     *
     * @param string $exprStr
     * @param        $min
     * @param        $max
     */
    public function __construct($exprStr, $min, $max)
    {
        $this->exprStr = $exprStr;
        $this->min     = $min;
        $this->max     = $max;
    }

    /**
     * @param \SQLBuilder\Driver\BaseDriver $driver
     * @param \SQLBuilder\ArgumentArray     $args
     *
     * @return string
     */
    public function toSql(BaseDriver $driver, ArgumentArray $args)
    {
        return $this->exprStr . ' BETWEEN ' . $driver->deflate($this->min, $args) . ' AND ' . $driver->deflate($this->max, $args);
    }

    /**
     * @param $array
     *
     * @return \SQLBuilder\Universal\Expr\BetweenExpr
     */
    public static function __set_state($array)
    {
        return new self($array['exprStr'], $array['min'], $array['max']);
    }
}
