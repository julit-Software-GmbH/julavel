<?php

namespace Julavel\Testing\Traits;

use Elastica\Client;
use Elasticsearch\Endpoints\Indices\Delete;
use Elasticsearch\Endpoints\Indices\Refresh;

trait ElasticIndices
{
    /**
     * Delete all Elasticsearch indices in current namespace.
     *
     * @throws \RuntimeException
     *
     * @return void
     */
    public function deleteIndices()
    {
        $client = app(Client::class);
        $prefix = env('ELASTIC_PREFIX');

        if (empty($prefix)) {
            throw new \RuntimeException('ELASTIC_PREFIX is not set');
        }

        $endpoint = new Delete();
        $endpoint->setIndex($prefix . '*');
        $client->requestEndpoint($endpoint);
    }

    /**
     * Refresh all Elasticsearch indices in current namespace.
     *
     * This allows to search immediately for new documents.
     * Otherwise it can take up to one second.
     *
     * @throws \RuntimeException
     *
     * @return void
     */
    public function refreshIndices()
    {
        $client = app(Client::class);
        $prefix = env('ELASTIC_PREFIX');

        if (empty($prefix)) {
            throw new \RuntimeException('ELASTIC_PREFIX is not set');
        }

        $endpoint = new Refresh();
        $endpoint->setIndex($prefix . '*');
        $client->requestEndpoint($endpoint);
    }
}
