<?php

namespace Core\Database;

class Builder
{
    private $table = '';
    private $select = [];
    private $where = '';
    private $join = '';
    private $orderBy = '';
    private $groupBy = '';
    private $having = '';
    private $offset = '';
    private $limit = '';
    private $commandString = '';
}