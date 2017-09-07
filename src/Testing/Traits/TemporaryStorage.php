<?php

namespace Julavel\Testing\Traits;

trait TemporaryStorage
{
    /**
     * Create a temporary storage for the application.
     *
     * @return void
     */
    public function setTemporaryStorage()
    {
        $prefix = (
            'laravel-' .
            app('env') . '-'
            . basename(str_replace('\\', '/', get_class($this))) . '-'
            . $this->getName()
            . '.XXXXXXXXXX'
        );
        $storagePath = exec(sprintf('mktemp -d %s/%s', sys_get_temp_dir(), $prefix));

        $this->app->useStoragePath($storagePath);
        mkdir(storage_path('/app/public'), 0775, true);
        mkdir(storage_path('/framework/cache'), 0775, true);
        mkdir(storage_path('/framework/sessions'), 0775, true);
        mkdir(storage_path('/framework/views'), 0775, true);
        mkdir(storage_path('/logs'), 0775, true);

        $this->beforeApplicationDestroyed([$this, 'removeTemporaryStorage']);
    }

    /**
     * Remove temporary storage.
     *
     * @return void
     */
    public function removeTemporaryStorage()
    {
        if (env('KEEP_TEMP_STORAGE', false) === true) {
            printf('Keeping temporary storage[%s]%s', storage_path(), PHP_EOL);
        } elseif (0 === strpos(storage_path(), sys_get_temp_dir() . '/')) {
            \File::deleteDirectory(storage_path());
        } else {
            printf('Ignoring to remove invalid storage dir[%s]%s', storage_path(), PHP_EOL);
        }
    }
}
