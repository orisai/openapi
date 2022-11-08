<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Generator;
use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Logic\InvalidState;
use Orisai\Exceptions\Message;
use Orisai\OpenAPI\Enum\HeaderStyle;
use Orisai\OpenAPI\Spec\Example;
use Orisai\OpenAPI\Spec\Header;
use Orisai\OpenAPI\Spec\MediaType;
use Orisai\OpenAPI\Spec\Reference;
use PHPUnit\Framework\TestCase;
use stdClass;
use function fopen;

final class HeaderTest extends TestCase
{

	public function test(): void
	{
		$h1 = new Header();
		self::assertSame($h1->getStyle(), HeaderStyle::simple());
		self::assertSame([], $h1->toArray());

		$h2 = new Header();
		$h2->description = 'description';
		$h2->required = true;
		$h2->deprecated = true;
		$h2->allowEmptyValue = true;
		$h2->explode = true;
		$h2->allowReserved = true;
		$h2->setExample(null);
		$h2->schema->setExample('schema');

		$h2->examples['foo'] = $h2e1 = new Example();
		$h2e1->description = 'desc';
		$h2->examples['bar'] = $h2e2 = new Reference('ref');

		$h2->content['application/json'] = $h2c1 = new MediaType();
		$h2c1->setExample('example');
		$h2->content['application/xml'] = $h2c2 = new MediaType();

		self::assertSame($h2->getStyle(), HeaderStyle::simple());
		self::assertSame(
			[
				'description' => 'description',
				'required' => true,
				'deprecated' => true,
				'allowEmptyValue' => true,
				'explode' => true,
				'allowReserved' => true,
				'schema' => $h2->schema->toArray(),
				'example' => null,
				'examples' => [
					'foo' => $h2e1->toArray(),
					'bar' => $h2e2->toArray(),
				],
				'content' => [
					'application/json' => $h2c1->toArray(),
					'application/xml' => $h2c2->toArray(),
				],
			],
			$h2->toArray(),
		);
	}

	public function testSetValue(): void
	{
		$header = new Header();

		$header->setExample(null);
		self::assertNull($header->getExample());

		$header->setExample('string');
		self::assertSame('string', $header->getExample());

		$header->setExample($o = new stdClass());
		self::assertSame($o, $header->getExample());

		$header->setExample([$o]);
		self::assertSame([$o], $header->getExample());
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

		$header = new Header();

		$this->expectException(InvalidArgument::class);
		$this->expectExceptionMessage(<<<MSG
Context: Setting an example.
Problem: Value contains type '$unsupportedType', which is not allowed.
Solution: Change type to one of supported - scalar, null, array or stdClass.
MSG);

		Message::$lineLength = 150;
		$header->setExample($value);
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
		$header = new Header();

		self::assertFalse($header->hasExample());

		$this->expectException(InvalidState::class);
		$this->expectExceptionMessage(<<<'MSG'
Context: Getting the example value.
Problem: Example value is not set and so cannot be get.
Solution: Check with hasExample().
MSG);

		$header->getExample();
	}

}
