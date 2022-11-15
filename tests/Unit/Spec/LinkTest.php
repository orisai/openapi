<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Generator;
use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Logic\InvalidState;
use Orisai\Exceptions\Message;
use Orisai\OpenAPI\Spec\Link;
use Orisai\OpenAPI\Spec\Server;
use PHPUnit\Framework\TestCase;
use stdClass;
use function fopen;

final class LinkTest extends TestCase
{

	public function test(): void
	{
		$l1 = Link::forId('id');

		self::assertSame('id', $l1->getOperationId());
		self::assertNull($l1->getOperationRef());

		self::assertSame(
			[
				'operationId' => 'id',
			],
			$l1->toArray(),
		);

		$l2 = Link::forRef('ref');

		self::assertNull($l2->getOperationId());
		self::assertSame('ref', $l2->getOperationRef());

		self::assertFalse($l2->hasRequestBody());
		$l2->setRequestBody('requestBody');
		self::assertTrue($l2->hasRequestBody());
		self::assertSame('requestBody', $l2->getRequestBody());

		$l2->addParameter('foo', '1');
		$l2->addParameter('bar', '2');
		self::assertSame(
			[
				'foo' => '1',
				'bar' => '2',
			],
			$l2->getParameters(),
		);

		$l2->description = 'description';
		$l2->server = $s2 = new Server('https://example.com');
		$l2->addExtension('x-a', null);

		self::assertSame(
			[
				'operationRef' => 'ref',
				'parameters' => [
					'foo' => '1',
					'bar' => '2',
				],
				'requestBody' => 'requestBody',
				'description' => 'description',
				'server' => $s2->toArray(),
				'x-a' => null,
			],
			$l2->toArray(),
		);
	}

	public function testSetValue(): void
	{
		$link = Link::forId('id');

		$link->setRequestBody(null);
		self::assertNull($link->getRequestBody());

		$link->setRequestBody('string');
		self::assertSame('string', $link->getRequestBody());

		$link->setRequestBody($o = new stdClass());
		self::assertSame($o, $link->getRequestBody());

		$link->setRequestBody([$o]);
		self::assertSame([$o], $link->getRequestBody());
	}

	/**
	 * @param mixed $value
	 *
	 * @dataProvider provideUnsupportedRequestBody
	 * @runInSeparateProcess
	 */
	public function testUnsupportedRequestBody($value, string $unsupportedType): void
	{
		// Workaround - yielded resource is for some reason cast to 0
		if ($value === 'resource') {
			$value = fopen(__FILE__, 'r');
		}

		$link = Link::forId('id');

		$this->expectException(InvalidArgument::class);
		$this->expectExceptionMessage(<<<MSG
Context: Setting a Link requestBody.
Problem: Value contains type '$unsupportedType', which is not allowed.
Solution: Change type to one of supported - scalar, null, array or stdClass.
MSG);

		Message::$lineLength = 150;
		$link->setRequestBody($value);
	}

	public function provideUnsupportedRequestBody(): Generator
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

	public function testGetNoRequestBody(): void
	{
		$link = Link::forId('id');

		self::assertFalse($link->hasRequestBody());

		$this->expectException(InvalidState::class);
		$this->expectExceptionMessage(<<<'MSG'
Context: Getting the Link requestBody.
Problem: RequestBody is not set and so cannot be get.
Solution: Check with hasRequestBody().
MSG);

		$link->getRequestBody();
	}

}
