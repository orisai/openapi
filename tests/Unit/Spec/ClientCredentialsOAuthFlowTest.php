<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\ClientCredentialsOAuthFlow;
use PHPUnit\Framework\TestCase;

final class ClientCredentialsOAuthFlowTest extends TestCase
{

	public function test(): void
	{
		$f1 = new ClientCredentialsOAuthFlow(
			[],
			'https://example.com/api/oauth/token',
		);
		self::assertSame(
			[
				'scopes' => [],
				'tokenUrl' => 'https://example.com/api/oauth/token',
			],
			$f1->toArray(),
		);

		$f2 = new ClientCredentialsOAuthFlow(
			[
				'write:pets' => 'modify pets in your account',
				'read:pets' => 'read your pets',
			],
			'https://example.com/api/oauth/token',
		);
		$f2->refreshUrl = 'https://example.com/api/oauth/refresh';
		self::assertSame(
			[
				'scopes' => [
					'write:pets' => 'modify pets in your account',
					'read:pets' => 'read your pets',
				],
				'refreshUrl' => 'https://example.com/api/oauth/refresh',
				'tokenUrl' => 'https://example.com/api/oauth/token',
			],
			$f2->toArray(),
		);
	}

}
