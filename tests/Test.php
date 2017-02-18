<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class Test extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $cred = ['api_token' => 'tqqM0QU7BwIpjjXkgWHl4HqLWCDb3hsMJ0vV26kj3MYjF6XjU7'];
        
        $this->get('/');

        $this->seeJsonEquals(['Version' => '1.0']);

        $this->get('/api/v1');

        $this->seeJsonEquals(['Version' => '1.0']);

        $this->call('POST', '/api/v1/post', array_merge($cred, ['string' => 'egg']));
        
        $this->seeJsonEquals(['status' => 'Ok', 'message' => ['translation' => 'eggway']]);

        $this->call('POST', '/api/v1/post', array_merge($cred, ['string' => 'Quary']));
        
        $this->seeJsonEquals(['status' => 'Ok', 'message' => ['translation' => 'Aryquay']]);

        $this->call('POST', '/api/v1/post', array_merge($cred, ['string' => 'pig']));
        
        $this->seeJsonEquals(['status' => 'Ok', 'message' => ['translation' => 'igpay']]);

        $this->call('POST', '/api/v1/post', array_merge($cred, ['string' => 'Latin']));
        
        $this->seeJsonEquals(['status' => 'Ok', 'message' => ['translation' => 'Atinlay']]);

        $this->call('POST', '/api/v1/post', array_merge($cred, ['string' => 'banana']));
        
        $this->seeJsonEquals(['status' => 'Ok', 'message' => ['translation' => 'ananabay']]);

        $this->call('POST', '/api/v1/post', array_merge($cred, ['string' => 'trash']));
        
        $this->seeJsonEquals(['status' => 'Ok', 'message' => ['translation' => 'ashtray']]);

        $this->call('POST', '/api/v1/post', array_merge($cred, ['string' => 'dopest']));
        
        $this->seeJsonEquals(['status' => 'Ok', 'message' => ['translation' => 'opestday']]);

        $this->call('POST', '/api/v1/post', array_merge($cred, ['string' => 'cheers']));
        
        $this->seeJsonEquals(['status' => 'Ok', 'message' => ['translation' => 'eerschay']]);

        $this->call('POST', '/api/v1/post', array_merge($cred, ['string' => 'smile']));
        
        $this->seeJsonEquals(['status' => 'Ok', 'message' => ['translation' => 'ilesmay']]);

        $this->call('POST', '/api/v1/post', array_merge($cred, ['string' => 'eat']));
        
        $this->seeJsonEquals(['status' => 'Ok', 'message' => ['translation' => 'eatway']]);

        $this->call('POST', '/api/v1/post', array_merge($cred, ['string' => 'omelet']));
        
        $this->seeJsonEquals(['status' => 'Ok', 'message' => ['translation' => 'omeletway']]);

        $this->call('GET', '/api/v1/post/2', $cred);

        $this->seeJsonEquals(['status' => 'Ok', 'message' => ['translation' => 'Aryquay']]);

        $this->call('GET', '/api/v1/post/11', $cred);

        $this->seeJsonEquals(['status' => 'Ok', 'message' => ['translation' => 'omeletway']]);

        $this->call('GET', '/api/v1/post/6', $cred);

        $this->seeJsonEquals(['status' => 'Ok', 'message' => ['translation' => 'ashtray']]);

        $this->call('GET', '/api/v1/post/60', $cred);

        $this->seeJsonEquals(['status' => 'Error', 'message' => 'Couldn\'t find the requested translation']);
    }
}
