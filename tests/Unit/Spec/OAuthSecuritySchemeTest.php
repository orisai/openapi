<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\OAuthFlows;
use Orisai\OpenAPI\Spec\OAuthSecurityScheme;
use Orisai\OpenAPI\Spec\PasswordOAuthFlow;
use PHPUnit\Framework\TestCase;

final class OAuthSecuritySchemeTest extends TestCase
{

	public function test(): void
	{
		$f1 = new OAuthFlows();
		$s1 = new OAuthSecurityScheme($f1);
		self::assertSame(
			[
				'type' => 'oauth2',
				'flows' => $f1->toArray(),
			],
			$s1->toArray(),
		);

		$f2 = new OAuthFlows();
		$f2->password = new PasswordOAuthFlow(
			'https://example.com/api/oauth/token',
		);
		$s2 = new OAuthSecurityScheme($f2);
		$s2->description = 'description';
		$s2->addExtension('x-a', null);
		self::assertSame(
			[
				'type' => 'oauth2',
				'description' => 'description',
				'flows' => $f2->toArray(),
				'x-a' => null,
			],
			$s2->toArray(),
		);
	}

}
