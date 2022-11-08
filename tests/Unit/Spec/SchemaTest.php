<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Generator;
use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Logic\InvalidState;
use Orisai\Exceptions\Message;
use Orisai\OpenAPI\Spec\Discriminator;
use Orisai\OpenAPI\Spec\ExternalDocumentation;
use Orisai\OpenAPI\Spec\Schema;
use PHPUnit\Framework\TestCase;
use stdClass;
use function fopen;

final class SchemaTest extends TestCase
{

	public function test(): void
	{
		$s1 = new Schema();
		self::assertSame([], $s1->toArray());

		$s2 = new Schema();
		$d2 = new Discriminator('property');
		$s2->discriminator = $d2;
		$x2 = $s2->xml;
		$x2->name = 'name';

		$ed2 = new ExternalDocumentation('https://example.com');
		$s2->externalDocs = $ed2;

		$s2->setExample(null);

		self::assertSame(
			[
				'discriminator' => $d2->toArray(),
				'xml' => $x2->toArray(),
				'externalDocs' => $ed2->toArray(),
				'example' => null,
			],
			$s2->toArray(),
		);
	}

	public function testSetValue(): void
	{
		$schema = new Schema();

		$schema->setExample(null);
		self::assertNull($schema->getExample());

		$schema->setExample('string');
		self::assertSame('string', $schema->getExample());

		$schema->setExample($o = new stdClass());
		self::assertSame($o, $schema->getExample());

		$schema->setExample([$o]);
		self::assertSame([$o], $schema->getExample());
	}

	/**
	 * @param mixed $value
	 *
	 * @dataProvider provideUnsupportedValue
	 * @runInSeparateProcess
	 */
	public function testUnsupportedValue($value, string $unsupportedType): void
	{
		// Workaround - yielded resource is for some reason cast to 0
		if ($value === 'resource') {
			$value = fopen(__FILE__, 'r');
		}

		$schema = new Schema();

		$this->expectException(InvalidArgument::class);
		$this->expectExceptionMessage(<<<MSG
Context: Setting an example.
Problem: Value contains type '$unsupportedType', which is not allowed.
Solution: Change type to one of supported - scalar, null, array or stdClass.
MSG);

		Message::$lineLength = 150;
		$schema->setExample($value);
	}

	public function provideUnsupportedValue(): Generator
	{
		yield [InvalidArgument::create(), InvalidArgument::class];

		yield [
			[
				'a' => 'b',
				'foo' => [
					'bar' => [
						InvalidArgument::create(),
					],
				],
			],
			InvalidArgument::class,
		];

		yield [
			'resource',
			'resource (stream)',
		];
	}

	public function testGetNoValue(): void
	{
		$schema = new Schema();

		self::assertFalse($schema->hasExample());

		$this->expectException(InvalidState::class);
		$this->expectExceptionMessage(<<<'MSG'
Context: Getting the example value.
Problem: Example value is not set and so cannot be get.
Solution: Check with hasExample().
MSG);

		$schema->getExample();
	}

}
