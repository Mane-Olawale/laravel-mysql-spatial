<?php

namespace Grimzy\LaravelMysqlSpatial\Schema;

use Closure;
use Illuminate\Database\Schema\Blueprint as IlluminateBlueprint;
use Illuminate\Database\Schema\MySqlBuilder;
use ReflectionClass;

class Builder extends MySqlBuilder
{
    /**
     * Create a new command set with a Closure.
     *
     * @param string  $table
     * @param Closure $callback
     *
     * @return Blueprint
     */
    protected function createBlueprint($table, ?Closure $callback = null)
    {
        // Laravel 12+ Blueprint constructor expects (Connection, $table, $callback);
        // earlier versions expect ($table, $callback).
        $constructor = (new ReflectionClass(IlluminateBlueprint::class))->getConstructor();
        if ($constructor && $constructor->getNumberOfParameters() > 0) {
            $firstParamType = $constructor->getParameters()[0]->getType();
            if ($firstParamType && ! $firstParamType->isBuiltin()) {
                return new Blueprint($this->connection, $table, $callback);
            }
        }

        return new Blueprint($table, $callback);
    }
}
