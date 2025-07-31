<?php

namespace App;

interface Queryable
{
    public function toCreateQuery();
    public function toUpdateQuery();
    public function toDeleteQuery();
}