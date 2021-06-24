<?php

namespace Tests\Unit;

use App;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Filters\ApiFilter;

class ApiFilterTest extends TestCase
{


    public function testApiFilter()
    {
        $values  = $this->getFilterValues();
        $filters = $this->getFilters();

        $filter = new ApiFilter(null, $values);

        $filter->apply($filters);

        $filterData = $filter->matches;

        $urlString = $filter->getUrlString();

        $this->assertEquals(json_encode($this->getExpectedFilter()), json_encode($filterData));

        $expectedUrlString = "vendor=improwised&name=test+business";

        $this->assertEquals($expectedUrlString, $urlString);
    }


    private function getFilterValues()
    {

        $obj = new \stdClass();

        $obj->name   = "test business";
        $obj->vendor = "improwised";

        return $obj;
    }


    private function getFilters()
    {

        $names = [
            'vendor' => '=',
            'name'   => 'like',
        ];

        return $names;
    }


    private function getExpectedFilter()
    {

        return $expected = [
            [
                "vendor",
                "=",
                "improwised",
            ],
            [
                "name",
                "like",
                "%test business%",
            ],
        ];

        return $expected;
    }
}
