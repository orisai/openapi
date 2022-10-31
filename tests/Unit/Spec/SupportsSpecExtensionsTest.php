<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Generator;
use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Message;
use PHPUnit\Framework\TestCase;
use stdClass;
use Tests\Orisai\OpenAPI\Doubles\ExtendableSpecObject;
use function fopen;

final class SupportsSpecExtensionsTest extends TestCase
{

	private ExtendableSpecObject $object;

	protected function setUp(): void
	{
		parent::setUp();
		$this->object = new ExtendableSpecObject();
	}

	public function testExtensionContent(): void
	{
		self::assertSame([], $this->object->getExtensions());
		self::assertSame([], $this->object->toArray());

		$this->object->addExtension('x-a', null);
		$this->object->addExtension('x-a', '');
		$this->object->addExtension('x-b', []);
		$this->object->addExtension('x-c', null);
		$this->object->addExtension('x-d', 123);

		self::assertSame(
			[
				'x-a' => '',
				'x-b' => [],
				'x-c' => null,
				'x-d' => 123,
			],
			$this->object->getExtensions(),
		);
		self::assertSame(
			[
				'x-a' => '',
				'x-b' => [],
				'x-c' => null,
				'x-d' => 123,
			],
			$this->object->toArray(),
		);
	}

	public function testUnsupportedName(): void
	{
		$this->expectException(InvalidArgument::class);
		$this->expectExceptionMessage(<<<'MSG'
Context: Adding a spec extension with name 'y-a'.
Problem: Extension name musts start with 'x-'
MSG);

		$this->object->addExtension('y-a', null);
	}

	/**
	 * @param mixed $content
	 *
	 * @dataProvider provideUnsupportedContent
	 * @runInSeparateProcess
	 */
	public function testUnsupportedContent($content, string $unsupportedType): void
	{
		// Workaround - yielded resource is for some reason cast to 0
		if ($content === 'resource') {
			$content = fopen(__FILE__, 'r');
		}

		$this->expectException(InvalidArgument::class);
		$this->expectExceptionMessage(<<<MSG
Context: Adding a spec extension with name 'x-name'.
Problem: Extension contains value of type '$unsupportedType', which is not allowed.
Solution: Change value to one of supported - scalar, null or array.
MSG);

		Message::$lineLength = 150;
		$this->object->addExtension('x-name', $content);
	}

	public function provideUnsupportedContent(): Generator
	{
		yield [new stdClass(), stdClass::class];

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

}
