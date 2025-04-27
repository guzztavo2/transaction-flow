<?php

namespace App\Entities;

interface Entity
{
    public function save();

    public function toArray();

    public static function findById(int $id): ?self;

    public static function query();
}
