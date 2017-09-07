<?php

namespace Julavel\Testing\Traits;

use File;

trait RefreshStorage
{
    /**
     * Delete all files in the storage
     *
     * @return void
     */
    protected function refreshStorage()
    {
        File::deleteDirectory(storage_path(), true);
        mkdir(storage_path('/app/public'), 0775, true);
        mkdir(storage_path('/framework/cache'), 0775, true);
        mkdir(storage_path('/framework/sessions'), 0775, true);
        mkdir(storage_path('/framework/views'), 0775, true);
        mkdir(storage_path('/logs'), 0775, true);
    }
}
