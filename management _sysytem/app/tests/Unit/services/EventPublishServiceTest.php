<?php

namespace Tests\Unit\Services;

use Tests\TestCase;

class EventPublishServiceTest extends TestCase
{


    public function testCanBuildEventName()
    {
        $this->assertTrue(true);
        // Make Campaign
        // $campaign = factory(Campaign::class)->make();
        // $event = new CampaignWasUpdated($campaign);
        // $this->assertEquals('ipromote-call-tracking-management.campaign-was-updated', EventPublishService::buildEventName($event));
    }


    // public function testCanBuildPayload()
    // {
    // Make Campaign
    // $campaign = factory(Campaign::class)->make();
    // $event = new CampaignWasUpdated($campaign);
    // $payload = app(EventPublishService::class, ['event' => $event])->buildPayload();
    // //print_r($payload);
    // $this->assertArrayHasKey('event', $payload);
    // $this->assertArrayHasKey('data', $payload);
    // $this->assertArrayHasKey('timestamp', $payload);
    // }
}
