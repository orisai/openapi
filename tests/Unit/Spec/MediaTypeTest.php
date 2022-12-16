<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Generator;
use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Logic\InvalidState;
use Orisai\Exceptions\Message;
use Orisai\OpenAPI\Enum\EncodingStyle;
use Orisai\OpenAPI\Spec\Encoding;
use Orisai\OpenAPI\Spec\Example;
use Orisai\OpenAPI\Spec\MediaType;
use Orisai\OpenAPI\Spec\NullSchema;
use Orisai\OpenAPI\Spec\Reference;
use PHPUnit\Framework\TestCase;
use stdClass;
use function fopen;

final class MediaTypeTest extends TestCase
{

	public function test(): void
	{
		$mt1 = new MediaType();
		self::assertSame([], $mt1->toRaw());

		$mt2 = new MediaType();

		$mt2->schema = $mt2s = new NullSchema();
		$mt2s->setExample(null);

		$mt2->setExample(null);

		$mt2->addExample('foo', $h2ex1 = new Example());
		$mt2->addExample('bar', $h2ex2 = new Reference('ref'));
		self::assertSame(
			[
				'foo' => $h2ex1,
				'bar' => $h2ex2,
			],
			$mt2->getExamples(),
		);

		$mt2->addEncoding('foo', $h2en1 = new Encoding());
		$h2en1->setStyle(EncodingStyle::form());
		$mt2->addEncoding('bar', $h2en2 = new Encoding());
		self::assertSame(
			[
				'foo' => $h2en1,
				'bar' => $h2en2,
			],
			$mt2->getEncodings(),
		);

		$mt2->addExtension('x-a', null);

		self::assertSame(
			[
				'schema' => $mt2s->toRaw(),
				'example' => null,
				'examples' => [
					'foo' => $h2ex1->toRaw(),
					'bar' => $h2ex2->toRaw(),
				],
				'encoding' => [
					'foo' => $h2en1->toRaw(),
					'bar' => $h2en2->toRaw(),
				],
				'x-a' => null,
			],
			$mt2->toRaw(),
		);
	}

	public function testSetValue(): void
	{
		$mediaType = new MediaType();

		$mediaType->setExample(null);
		self::assertNull($mediaType->getExample());

		$mediaType->setExample('string');
		self::assertSame('string', $mediaType->getExample());

		$mediaType->setExample($o = new stdClass());
		self::assertSame($o, $mediaType->getExample());

		$mediaType->setExample([$o]);
		self::assertSame([$o], $mediaType->getExample());
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

		$mediaType = new MediaType();

		$this->expectException(InvalidArgument::class);
		$this->expectExceptionMessage(<<<MSG
Context: Setting a MediaType example.
Problem: Value contains type '$unsupportedType', which is not allowed.
Solution: Change type to one of supported - scalar, null, array or stdClass.
MSG);

		Message::$lineLength = 150;
		$mediaType->setExample($value);
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
		$mediaType = new MediaType();

		self::assertFalse($mediaType->hasExample());

		$this->expectException(InvalidState::class);
		$this->expectExceptionMessage(<<<'MSG'
Context: Getting the MediaType example.
Problem: Example is not set and so cannot be get.
Solution: Check with hasExample().
MSG);

		$mediaType->getExample();
	}

}
