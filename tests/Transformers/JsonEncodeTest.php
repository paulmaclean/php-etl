<?php

namespace Tests\Transformers;

use Tests\TestCase;
use Marquine\Etl\Transformers\JsonEncode;

class JsonEncodeTest extends TestCase
{
    protected $items = [
        ['id' => '1', 'data' => ['name' => 'John Doe', 'email' => 'johndoe@email.com']],
        ['id' => '2', 'data' => ['name' => 'Jane Doe', 'email' => 'janedoe@email.com']],
    ];

    /** @test */
    public function convert_all_columns_to_json()
    {
        $pipeline = $this->createMock('Marquine\Etl\Pipeline');

        $transformer = new JsonEncode;

        $results = array_map($transformer->handler($pipeline), $this->items);

        $expected = [
            ['id' => '"1"', 'data' => '{"name":"John Doe","email":"johndoe@email.com"}'],
            ['id' => '"2"', 'data' => '{"name":"Jane Doe","email":"janedoe@email.com"}'],
        ];

        $this->assertEquals($expected, $results);
    }

    /** @test */
    public function convert_specific_columns_to_json()
    {
        $pipeline = $this->createMock('Marquine\Etl\Pipeline');

        $transformer = new JsonEncode;

        $transformer->columns = ['data'];

        $results = array_map($transformer->handler($pipeline), $this->items);

        $expected = [
            ['id' => '1', 'data' => '{"name":"John Doe","email":"johndoe@email.com"}'],
            ['id' => '2', 'data' => '{"name":"Jane Doe","email":"janedoe@email.com"}'],
        ];

        $this->assertEquals($expected, $results);
    }
}
