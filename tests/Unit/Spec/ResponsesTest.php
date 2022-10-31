<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Generator;
use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\OpenAPI\Spec\Response;
use Orisai\OpenAPI\Spec\Responses;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;
use function array_keys;
use function json_encode;
use const JSON_PRETTY_PRINT;

final class ResponsesTest extends TestCase
{

	public function test(): void
	{
		$rs1 = new Responses();
		self::assertSame([], $rs1->toArray());

		$rs2 = new Responses();

		$rs2rsd = new Response('default');
		$rs2->addResponse('default', $rs2rsd);

		$rs2r1 = new Response('deleted');
		$rs2->addResponse(204, $rs2r1);

		$rs2r2 = new Response('not found');
		$rs2->addResponse(404, $rs2r2);

		$rs2->addExtension('x-a', null);

		self::assertSame(
			[
				204 => $rs2r1,
				404 => $rs2r2,
				'default' => $rs2rsd,
			],
			$rs2->getResponses(),
		);
		self::assertSame(
			[
				204 => $rs2r1->toArray(),
				404 => $rs2r2->toArray(),
				'default' => $rs2rsd->toArray(),
				'x-a' => null,
			],
			$rs2->toArray(),
		);
	}

	/**
	 * @param int|string $code
	 *
	 * @dataProvider provideInvalidCode
	 */
	public function testInvalidCode($code): void
	{
		$rs = new Responses();

		$this->expectException(InvalidArgument::class);
		$this->expectExceptionMessage(<<<MSG
Context: Adding response with code '$code'.
Problem: Only codes in range 100-599, '1XX', '2XX', '3XX', '4XX', '5XX' and
         'default' are allowed.
MSG);

		$rs->addResponse($code, new Response('description'));
	}

	public function provideInvalidCode(): Generator
	{
		yield [99];
		yield [600];
		yield ['6XX'];
		yield ['5xx'];
		yield ['defaultt'];
	}

	public function testResponsesOrder(): void
	{
		$rs = new Responses();

		$rs->addResponse('default', new Response('default'));
		$rs->addResponse('5XX', new Response('5XX'));
		$rs->addResponse(599, new Response('599'));
		$rs->addResponse(500, new Response('500'));
		$rs->addResponse('4XX', new Response('4XX'));
		$rs->addResponse(499, new Response('499'));
		$rs->addResponse(400, new Response('400'));
		$rs->addResponse('3XX', new Response('3XX'));
		$rs->addResponse(399, new Response('399'));
		$rs->addResponse(300, new Response('300'));
		$rs->addResponse('2XX', new Response('2XX'));
		$rs->addResponse(299, new Response('299'));
		$rs->addResponse(200, new Response('200'));
		$rs->addResponse('1XX', new Response('1XX'));
		$rs->addResponse(199, new Response('199'));
		$rs->addResponse(100, new Response('100'));

		self::assertSame(
			[100, 199, '1XX', 200, 299, '2XX', 300, 399, '3XX', 400, 499, '4XX', 500, 599, '5XX', 'default'],
			array_keys($rs->toArray()),
		);
	}

	public function testKeyIsString(): void
	{
		$rs = new Responses();
		$rs->addResponse(200, new Response('200'));

		self::assertSame(
			<<<'JSON'
{
    "200": {
        "description": "200"
    }
}
JSON,
			json_encode($rs->toArray(), JSON_PRETTY_PRINT),
		);

		// Key is a number instead of string - is it even possible to make it string?
		self::assertSame(
			<<<'YAML'
200:
    description: '200'

YAML,
			Yaml::dump($rs->toArray()),
		);
	}

	public function testNumericStringCode(): void
	{
		$rs = new Responses();
		$rs->addResponse('200', $r = new Response('200'));

		self::assertSame(
			[200 => $r->toArray()],
			$rs->toArray(),
		);
	}

}
