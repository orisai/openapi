<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\ImplicitOAuthFlow;
use PHPUnit\Framework\TestCase;

final class ImplicitOAuthFlowTest extends TestCase
{

	public function test(): void
	{
		$f1 = new ImplicitOAuthFlow(
			[],
			'https://example.com/api/oauth/dialog',
		);
		self::assertSame(
			[
				'scopes' => [],
				'authorizationUrl' => 'https://example.com/api/oauth/dialog',
			],
			$f1->toArray(),
		);

		$f2 = new ImplicitOAuthFlow(
			[
				'write:pets' => 'modify pets in your account',
				'read:pets' => 'read your pets',
			],
			'https://example.com/api/oauth/dialog',
		);
		$f2->refreshUrl = 'https://example.com/api/oauth/refresh';
		self::assertSame(
			[
				'scopes' => [
					'write:pets' => 'modify pets in your account',
					'read:pets' => 'read your pets',
				],
				'refreshUrl' => 'https://example.com/api/oauth/refresh',
				'authorizationUrl' => 'https://example.com/api/oauth/dialog',
			],
			$f2->toArray(),
		);
	}

}
