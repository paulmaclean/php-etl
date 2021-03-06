<?php

namespace Tests\Extractors;

use Tests\TestCase;
use Marquine\Etl\Row;
use Marquine\Etl\Extractors\Query;

class QueryTest extends TestCase
{
    /** @test */
    public function default_options()
    {
        $statement = $this->createMock('PDOStatement');
        $statement->expects($this->once())->method('execute')->with([]);
        $statement->expects($this->exactly(3))->method('fetch')->will($this->onConsecutiveCalls(['row1'], ['row2'], null));

        $connection = $this->createMock('PDO');
        $connection->expects($this->once())->method('prepare')->with('select query')->willReturn($statement);

        $manager = $this->createMock('Marquine\Etl\Database\Manager');
        $manager->expects($this->once())->method('pdo')->with('default')->willReturn($connection);

        $extractor = new Query($manager);

        $extractor->input('select query');

        $this->assertEquals([new Row(['row1']), new Row(['row2'])], iterator_to_array($extractor->extract()));
    }

    /** @test */
    public function custom_connection_and_bindings()
    {
        $statement = $this->createMock('PDOStatement');
        $statement->expects($this->once())->method('execute')->with('bindings');
        $statement->expects($this->exactly(3))->method('fetch')->will($this->onConsecutiveCalls(['row1'], ['row2'], null));

        $connection = $this->createMock('PDO');
        $connection->expects($this->once())->method('prepare')->with('select query')->willReturn($statement);

        $manager = $this->createMock('Marquine\Etl\Database\Manager');
        $manager->expects($this->once())->method('pdo')->with('connection')->willReturn($connection);

        $extractor = new Query($manager);

        $extractor->input('select query');
        $extractor->options([
            'connection' => 'connection',
            'bindings' => 'bindings',
        ]);

        $this->assertEquals([new Row(['row1']), new Row(['row2'])], iterator_to_array($extractor->extract()));
    }
}
