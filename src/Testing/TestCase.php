<?php

namespace Julavel\Testing;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Julavel\Testing\Traits\ElasticIndices;
use Julavel\Testing\Traits\ElasticMock;
use Julavel\Testing\Traits\TemporaryStorage;
use Julavel\Testing\Traits\TruncateDatabase;

abstract class TestCase extends BaseTestCase
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

        if (isset($uses[TemporaryStorage::class])) {
            $this->setTemporaryStorage();
        }

        if (isset($uses[ElasticIndices::class])) {
            $this->deleteIndices();
        }

        return $uses;
    }
}
