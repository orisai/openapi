<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Generator;
use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\OpenAPI\Enum\SecuritySchemeType;
use Orisai\OpenAPI\Spec\HttpSecurityScheme;
use PHPUnit\Framework\TestCase;

final class HttpSecuritySchemeTest extends TestCase
{

	public function test(): void
	{
		$s1 = new HttpSecurityScheme('Basic');
		self::assertSame(SecuritySchemeType::http(), $s1->getType());
		self::assertSame('Basic', $s1->getScheme());
		self::assertSame(
			[
				'type' => 'http',
				'scheme' => 'Basic',
			],
			$s1->toArray(),
		);

		$s2 = new HttpSecurityScheme('Bearer');
		$s2->description = 'description';
		$s2->setBearerFormat('JWT');
		$s2->addExtension('x-a', null);
		self::assertSame('Bearer', $s2->getScheme());
		self::assertSame(
			[
				'type' => 'http',
				'description' => 'description',
				'scheme' => 'Bearer',
				'bearerFormat' => 'JWT',
				'x-a' => null,
			],
			$s2->toArray(),
		);
	}

	/**
	 * @dataProvider provideBearerFormat
	 */
	public function testBearerFormat(string $scheme): void
	{
		$s = new HttpSecurityScheme($scheme);
		$s->setBearerFormat('JWT');
		self::assertSame(
			[
				'type' => 'http',
				'scheme' => $scheme,
				'bearerFormat' => 'JWT',
			],
			$s->toArray(),
		);
	}

	public function provideBearerFormat(): Generator
	{
		yield ['Bearer'];
		yield ['bearer'];
		yield ['BEARER'];
		yield ['bEaReR'];
	}

	public function testNotBearerFormat(): void
	{
		$s = new HttpSecurityScheme('bearless');

		$this->expectException(InvalidArgument::class);
		$this->expectExceptionMessage(<<<'MSG'
Context: Setting a bearer format for security scheme 'bearless'.
Problem: Bearer format is supported only by scheme 'Bearer'.
MSG);

		$s->setBearerFormat('JWT');
	}

}
