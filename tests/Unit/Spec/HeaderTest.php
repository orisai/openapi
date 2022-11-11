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
		self::assertFalse($h1->getExplode());
		self::assertSame([], $h1->toArray());

		$h2 = new Header();
		$h2->description = 'description';
		$h2->deprecated = true;
		$h2->setExample(null);
		$h2->schema->setExample('schema');

		$h2->addExample('foo', $h2e1 = new Example());
		$h2e1->description = 'desc';
		$h2->addExample('bar', $h2e2 = new Reference('ref'));
		self::assertSame(
			[
				'foo' => $h2e1,
				'bar' => $h2e2,
			],
			$h2->getExamples(),
		);

		$h2->addContent('application/json', $h2c1 = new MediaType());
		$h2c1->setExample(null);

		$h2->addExtension('x-a', null);

		self::assertSame($h2->getStyle(), HeaderStyle::simple());
		self::assertSame(
			[
				'description' => 'description',
				'deprecated' => true,
				'schema' => $h2->schema->toArray(),
				'example' => null,
				'examples' => [
					'foo' => $h2e1->toArray(),
					'bar' => $h2e2->toArray(),
				],
				'content' => [
					'application/json' => $h2c1->toArray(),
				],
				'x-a' => null,
			],
			$h2->toArray(),
		);
	}

	public function testRequired(): void
	{
		$header = new Header();
		self::assertNotContains('required', $header->toArray());

		$header->setRequired();
		self::assertTrue($header->toArray()['required']);

		$header->setRequired(false);
		self::assertNotContains('required', $header->toArray());
	}

	public function testExplode(): void
	{
		$header = new Header();
		self::assertFalse($header->getExplode());

		$header->setStyle(HeaderStyle::simple());
		self::assertFalse($header->getExplode());

		$header->setStyle(HeaderStyle::simple(), false);
		self::assertFalse($header->getExplode());

		$header->setStyle(HeaderStyle::simple(), true);
		self::assertTrue($header->getExplode());
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

	public function testMultipleContents(): void
	{
		$header = new Header();
		self::assertSame([], $header->getContent());

		$mt1 = new MediaType();
		$mt1->setExample(null);
		$header->addContent('application/json', $mt1);

		// Same media type is okay
		$mt2 = new MediaType();
		$header->addContent('application/json', $mt2);

		$this->expectException(InvalidState::class);
		$this->expectExceptionMessage(
			<<<'MSG'
Context: Adding content with media type 'application/xml' to a Header.
Problem: Header content can contain only one entry, given one is second.
MSG,
		);

		$header->addContent('application/xml', new MediaType());
	}

}
