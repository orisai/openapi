<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Enum\SecuritySchemeIn;
use Orisai\OpenAPI\Enum\SecuritySchemeType;
use Orisai\OpenAPI\Spec\ApiKeySecurityScheme;
use PHPUnit\Framework\TestCase;

final class ApiKeySecuritySchemeTest extends TestCase
{

	public function test(): void
	{
		$s1 = new ApiKeySecurityScheme('api_key', SecuritySchemeIn::header());
		self::assertSame(SecuritySchemeType::apiKey(), $s1->getType());
		self::assertSame(
			[
				'type' => 'apiKey',
				'name' => 'api_key',
				'in' => 'header',
			],
			$s1->toArray(),
		);

		$s2 = new ApiKeySecurityScheme('access_cookie', SecuritySchemeIn::cookie());
		$s2->description = 'description';
		$s2->addExtension('x-a', null);
		self::assertSame(
			[
				'type' => 'apiKey',
				'description' => 'description',
				'name' => 'access_cookie',
				'in' => 'cookie',
				'x-a' => null,
			],
			$s2->toArray(),
		);
	}

}
