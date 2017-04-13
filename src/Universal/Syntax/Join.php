<?php

namespace SQLBuilder\Universal\Syntax;

use BadMethodCallException;
use SQLBuilder\ArgumentArray;
use SQLBuilder\Driver\BaseDriver;
use SQLBuilder\Driver\MySQLDriver;
use SQLBuilder\MySQL\Traits\IndexHintTrait;
use SQLBuilder\ToSqlInterface;

/**
 * Class Join
 *
 * @package SQLBuilder\Universal\Syntax
 *
 * @author  Yo-An Lin (c9s) <cornelius.howl@gmail.com>
 * @author  Aleksey Ilyenko <assada.ua@gmail.com>
 */
class Join implements ToSqlInterface
{
    use IndexHintTrait;

    /**
     * @var \SQLBuilder\Universal\Syntax\Conditions
     */
    public $conditions;

    /**
     * @var null|string
     */
    public $alias;

    /**
     * @var null|string
     */
    public $table;

    /**
     * @var null|string
     */
    protected $joinType;

    /**
     * Join constructor.
     *
     * @param string      $table
     * @param null|string $alias
     * @param null|string $joinType
     */
    public function __construct($table, $alias = null, $joinType = null)
    {
        $this->table      = $table;
        $this->alias      = $alias;
        $this->joinType   = $joinType;
        $this->conditions = new Conditions();
    }

    /**
     * @return $this
     */
    public function left()
    {
        $this->joinType = 'LEFT';

        return $this;
    }

    /**
     * @return $this
     */
    public function right()
    {
        $this->joinType = 'RIGHT';

        return $this;
    }

    /**
     * @return $this
     */
    public function inner()
    {
        $this->joinType = 'INNER';

        return $this;
    }

    /**
     * @param null|string $conditionExpr
     * @param array       $args
     *
     * @return \SQLBuilder\Universal\Syntax\Conditions
     */
    public function on($conditionExpr = null, array $args = [])
    {
        if (is_string($conditionExpr)) {
            $this->conditions->raw($conditionExpr, $args);
        }

        return $this->conditions;
    }

    /**
     * @param \SQLBuilder\Driver\BaseDriver $driver
     * @param \SQLBuilder\ArgumentArray     $args
     *
     * @return string
     */
    public function toSql(BaseDriver $driver, ArgumentArray $args)
    {
        $sql = '';

        if ($this->joinType) {
            $sql .= ' ' . $this->joinType;
        }

        $sql .= ' JOIN ' . $this->table;

        if ($this->alias) {
            $sql .= ' AS ' . $this->alias;
        }

        if ($driver instanceof MySQLDriver) {
            $sql .= $this->buildIndexHintClause($driver, $args);
        }

        if ($this->conditions->hasExprs()) {
            $sql .= ' ON (' . $this->conditions->toSql($driver, $args) . ')';
        }

        return $sql;
    }

    /**
     * @param string $alias
     *
     * @return $this
     */
    public function _as($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * @param $m
     * @param $a
     *
     * @return \SQLBuilder\Universal\Syntax\Join
     * @throws \BadMethodCallException
     */
    public function __call($m, $a)
    {
        if ($m === 'as') {
            return $this->_as($a[0]);
        }
        throw new BadMethodCallException("Invalid method call: $m");
    }
}
