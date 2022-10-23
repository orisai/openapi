<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\OpenIDConnectSecurityScheme;
use PHPUnit\Framework\TestCase;

final class OpenIDConnectSecuritySchemeTest extends TestCase
{

	public function test(): void
	{
		$s1 = new OpenIDConnectSecurityScheme('https://example.com');
		self::assertSame(
			[
				'type' => 'openIdConnect',
				'openIdConnectUrl' => 'https://example.com',
			],
			$s1->toArray(),
		);

		$s2 = new OpenIDConnectSecurityScheme('https://example.com');
		$s2->description = 'description';
		$s2->addExtension('x-a', null);
		self::assertSame(
			[
				'type' => 'openIdConnect',
				'description' => 'description',
				'openIdConnectUrl' => 'https://example.com',
				'x-a' => null,
			],
			$s2->toArray(),
		);
	}

}
