<?php

namespace PHPixie\ORM\Repositories;

interface Repository
{
    public function modelName();
    public function save($entity);
    public function delete($entity);
    public function load($data);
    public function create();
    public function query();
}
