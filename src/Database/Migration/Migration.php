<?php

declare(strict_types=1);

namespace App\Database\Migration;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

abstract class Migration
{
    abstract public function up(Builder $schema): void;
    abstract public function down(Builder $schema): void;

    public function getName(): string
    {
        return (new \ReflectionClass($this))->getShortName();
    }
}