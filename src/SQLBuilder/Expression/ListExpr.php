<?php
namespace SQLBuilder\Expression;

use SQLBuilder\Expression\Expr;
use SQLBuilder\Driver\BaseDriver;
use SQLBuilder\ParamMarker;
use LogicException;

class ListExpr extends Expr { 

    public $set;

    public function __construct(array $set) {
        $this->set = $set;
    }

    public function append($val) {
        $this->set[] = $val;
    }

    public function renderSet(BaseDriver $driver, array $set) {
        return array_map(function($val) use($driver) {
            return $driver->deflate($val);
        }, $set);
    }

    public function toSql(BaseDriver $driver) {
        return '(' . join(',', $this->renderSet($driver, $this->set)) . ')';
    }

}

