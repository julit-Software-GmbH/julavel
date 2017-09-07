<?php

namespace Julavel\Testing\Traits;

use Illuminate\Support\Facades\DB;

trait TruncateDatabase
{
    /**
     * Turncate all tables excluding the migrations table.
     *
     * This allows us to reset the database in fast manner.
     *
     * @return void
     */
    protected function truncateDatabase()
    {
        $database = config('database.connections')[config('database.default')]['database'];
        if ($database === ':memory:') {
            print("Consider RefreshDatabase instead of TruncateDatabase for in-memory databases");
        }
        $res = DB::select(DB::raw(sprintf(
            "SELECT TABLE_NAME from INFORMATION_SCHEMA.TABLES "
            . "where table_schema = '%s' and table_name != 'migrations';",
            env('DB_DATABASE')
        )));
        DB::beginTransaction();
        DB::statement(DB::raw('SET FOREIGN_KEY_CHECKS=0;'));
        foreach (array_column($res, "TABLE_NAME") as $tableName) {
            DB::statement(DB::raw(sprintf(
                "TRUNCATE TABLE %s.%s;",
                $database,
                $tableName
            )));
        }
        DB::statement(DB::raw('SET FOREIGN_KEY_CHECKS=1;'));
        DB::commit();
    }
}
