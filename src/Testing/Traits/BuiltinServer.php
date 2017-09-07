<?php

namespace Julavel\Testing\Traits;

use RuntimeException;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

trait BuiltinServer
{
    /**
     * The builtin web server process instance.
     *
     * @var \Symfony\Component\Process\Process
     */
    protected static $serverProcess;

    /**
     * Server standard output
     *
     * @var sting
     */
    protected static $serverOutput;

    /**
     * Server error output
     *
     * @var sting
     */
    protected static $serverErrorOutput;

    /**
     * Start the builtin web server process.
     *
     * @return void
     */
    public static function startBuiltinServer()
    {
        static::$serverProcess = static::buildBuildinServerProcess();

        static::$serverProcess->start();

        static::afterClass(function () {
            static::stopBuiltinServer();
        });
    }

    /**
     * Stop the builtin web server process.
     *
     * @return void
     */
    public static function stopBuiltinServer()
    {
        if (static::$serverProcess) {
            static::$serverProcess->stop();
        }
    }

    /**
     * Returns an array containg the stdout and stderr since last call
     *
     * @return array
     */
    public static function getIncrementalOutputBuiltinServer()
    {
        return [
            "stdout" => static::$serverProcess->getIncrementalOutput(),
            "stderr" => static::$serverProcess->getIncrementalErrorOutput()
        ];
    }

    /**
     * Build the process to run the buildin web server
     *
     * @return \Symfony\Component\Process\Process
     */
    protected static function buildBuildinServerProcess()
    {
        $pathExec   = realpath((new PhpExecutableFinder)->find(false));
        $pathServer = realpath(sprintf('%s/../../../server.php', __DIR__));
        $pathBase   = realpath(sprintf('%s/../../../public', __DIR__));

        return new Process([$pathExec, '-S=127.0.0.1:8000', $pathServer], $pathBase);
    }

    public function registerStoreServerLog()
    {
        $this->beforeApplicationDestroyed([$this, 'storeServerLogs']);
    }

    /**
     * Copy the laravel.log to the tests dir
     *
     * @return void
     */
    public function storeServerLogs()
    {
        $destDir                = base_path('tests/Browser/server');
        $destPrefix             = $destDir . '/' . $this->getName() . '-';
        $destLaravelLog         = $destPrefix . 'laravel.log';
        $destServerlLog         = $destPrefix . 'output.log';
        $destServerErrorLog    = $destPrefix . 'error.log';
        $sourceLaravelLog       = storage_path('logs/laravel.log');

        if (!file_exists($destDir)) {
            mkdir($destDir);
        }

        // Move laravel.log
        if (file_exists($sourceLaravelLog)) {
            rename($sourceLaravelLog, $destLaravelLog);
        }

        // Get the output from the builtin server
        $output = static::getIncrementalOutputBuiltinServer();
        if (!empty($output['stdout'])) {
            file_put_contents($destServerLog, $output['stdout']);
        }
        if (!empty($output['stderr'])) {
            file_put_contents($destServerErrorLog, $output['stderr']);
        }
    }
}
