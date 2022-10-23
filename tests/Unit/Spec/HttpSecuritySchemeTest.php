<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\HttpSecurityScheme;
use PHPUnit\Framework\TestCase;

final class HttpSecuritySchemeTest extends TestCase
{

	public function test(): void
	{
		$s1 = new HttpSecurityScheme('Basic');
		self::assertSame(
			[
				'type' => 'http',
				'scheme' => 'Basic',
			],
			$s1->toArray(),
		);

		$s2 = new HttpSecurityScheme('Bearer');
		$s2->description = 'description';
		$s2->bearerFormat = 'JWT';
		$s2->addExtension('x-a', null);
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

}
