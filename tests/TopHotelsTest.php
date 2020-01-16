<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class TopHotelsTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->call('POST', '/api/hotel/topHotels');
        $this->assertEquals(200, $response->status());
        $this->assertTrue(true);
    }
}
