<?php

use AblaFahita\User;

class ApiTestCase extends TestCase
{
    public function createApplication()
    {
        $app = parent::createApplication();

        Route::group(['namespace' => 'AblaFahita\Http\Controllers'], function(){
            require app_path().'/Http/routes/api.php';
        });

        return $app;
    }

    protected function flushDb()
    {
        // Empty all the nodes before seeding
        $connection = (new User)->getConnection();
        $client = $connection->getClient();

        $batch = $client->startBatch();
        // Remove all relationships and related nodes
        $query = new \Everyman\Neo4j\Cypher\Query($client, 'MATCH (n), (m)-[r]-(c) DELETE n,m,r,c');
        $query->getResultSet();
        // Remove singular nodes with no relations
        $query = new \Everyman\Neo4j\Cypher\Query($client, 'MATCH (n) DELETE n');
        $query->getResultSet();
        $batch->commit();
    }
}
