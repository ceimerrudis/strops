<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ExampleTest extends TestCase
{
    use DatabaseMigrations;
    
    //Pārbauda vai publiskā lapa ir pieejama
    public function publicPageUp()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
