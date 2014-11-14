<?php

namespace PHPixie\ORM\Conditions;

interface Builder extends \PHPixie\Database\Conditions\Builder
{
    public function addWhereOperatorCondition($logic, $negate, $field, $operator, $values);
    public function addWherePlaceholder($logic = 'and', $negate = false, $allowEmpty = true);
    public function startWhereConditionGroup($logic = 'and', $negate = false);
    
    public function where();
    public function andWhere();
    public function orWhere();
    public function xorWhere();
    public function whereNot();
    public function andWhereNot();
    public function orWhereNot();
    public function xorWhereNot();
    public function startWhereGroup();
    public function startAndWhereGroup();
    public function startOrWhereGroup();
    public function startXorWhereGroup();
    public function startWhereNotGroup();
    public function startAndWhereNotGroup();
    public function startOrWhereNotGroup();
    public function startXorWhereNotGroup();
    public function endWhereGroup();
    
    
    public function addInCondition($logic, $negate, $items, $relationship = null);
    
    public function in($items);
    public function andIn($items);
    public function orIn($items);
    public function xorIn($items);
    public function notIn($items);
    public function andNotIn($items);
    public function orNotIn($items);
    public function xorNotIn($items);
    
    
    public function addRelatedToCondition($logic, $negate, $relationship, $items);
    public function startRelatedToConditionGroup($relationship, $logic = 'and', $negate = false);
    
    public function relatedTo($relationship, $items);
    public function andRelatedTo($relationship, $items);
    public function orRelatedTo($relationship, $items);
    public function xorRelatedTo($relationship, $items);
    public function notRelatedTo($relationship, $items);
    public function andNotRelatedTo($relationship, $items);
    public function orNotRelatedTo($relationship, $items);
    public function xorNotRelatedTo($relationship, $items);
    
    public function startRelatedToGroup($relationship);
    public function startAndRelatedToGroup($relationship);
    public function startOrRelatedToGroup($relationship);
    public function startXorRelatedToGroup($relationship);
    public function startNotRelatedToGroup($relationship);
    public function startAndNotRelatedToGroup($relationship);
    public function startOrNotRelatedToGroup($relationship);
    public function startXorNotRelatedToGroup($relationship);
    public function endRelatedToGroup();
}