<?php

namespace Julavel\Testing;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\TestCase as BaseTestCase;
use Julavel\Testing\Traits\BuiltinServer;
use Julavel\Testing\Traits\ElasticIndices;
use Julavel\Testing\Traits\RefreshStorage;
use Julavel\Testing\Traits\TruncateDatabase;

abstract class DuskTestCase extends BaseTestCase
{
    /**
     * @inheritdoc
     */
    public function setUpTraits()
    {
        $uses = parent::setUpTraits();

        if (isset($uses[TruncateDatabase::class])) {
            $this->truncateDatabase();
        }

        if (isset($uses[RefreshStorage::class])) {
            $this->refreshStorage();
        }

        if (isset($uses[ElasticIndices::class])) {
            $this->deleteIndices();
        }

        if (isset($uses[BuiltinServer::class])) {
            $this->registerStoreServerLog();
        }

        if (isset($uses[RefreshDatabase::class])) {
            $this->refreshDatabase();
        }

        return $uses;
    }
}
