<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Generator;
use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Logic\InvalidState;
use Orisai\Exceptions\Message;
use Orisai\OpenAPI\Spec\Example;
use PHPUnit\Framework\TestCase;
use stdClass;
use function fopen;

final class ExampleTest extends TestCase
{

	public function test(): void
	{
		$e1 = new Example();
		self::assertNull($e1->getExternalValue());
		self::assertSame([], $e1->toArray());

		$e2 = new Example();
		$e2->summary = 'summary';
		$e2->description = 'description';
		$e2->setExternalValue('https://example.com/user-example.json');
		$e2->addExtension('x-a', null);
		self::assertFalse($e2->hasValue());
		self::assertSame('https://example.com/user-example.json', $e2->getExternalValue());
		self::assertSame(
			[
				'summary' => 'summary',
				'description' => 'description',
				'externalValue' => 'https://example.com/user-example.json',
				'x-a' => null,
			],
			$e2->toArray(),
		);

		$e2 = new Example();
		$e2->setValue(null);
		self::assertTrue($e2->hasValue());
		self::assertNull($e2->getValue());
		self::assertSame(
			[
				'value' => null,
			],
			$e2->toArray(),
		);
	}

	public function testValueOverride(): void
	{
		$e1 = new Example();
		$e1->setValue(null);
		$e1->setExternalValue('https://example.com/user-example.json');
		self::assertSame(
			[
				'externalValue' => 'https://example.com/user-example.json',
			],
			$e1->toArray(),
		);

		$e1 = new Example();
		$e1->setExternalValue('https://example.com/user-example.json');
		$e1->setValue(null);
		self::assertSame(
			[
				'value' => null,
			],
			$e1->toArray(),
		);
	}

	public function testSetValue(): void
	{
		$e = new Example();

		$e->setValue(null);
		self::assertNull($e->getValue());

		$e->setValue('string');
		self::assertSame('string', $e->getValue());

		$e->setValue($o = new stdClass());
		self::assertSame($o, $e->getValue());

		$e->setValue([$o]);
		self::assertSame([$o], $e->getValue());
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

		$example = new Example();

		$this->expectException(InvalidArgument::class);
		$this->expectExceptionMessage(<<<MSG
Context: Setting an example.
Problem: Value contains type '$unsupportedType', which is not allowed.
Solution: Change type to one of supported - scalar, null, array or stdClass.
MSG);

		Message::$lineLength = 150;
		$example->setValue($value);
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
		$example = new Example();

		self::assertFalse($example->hasValue());

		$this->expectException(InvalidState::class);
		$this->expectExceptionMessage(<<<'MSG'
Context: Getting the example value.
Problem: Example value is not set and so cannot be get.
Solution: Check with hasValue().
MSG);

		$example->getValue();
	}

}
