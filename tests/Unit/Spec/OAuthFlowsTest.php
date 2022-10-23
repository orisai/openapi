<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\AuthorizationCodeOAuthFlow;
use Orisai\OpenAPI\Spec\ClientCredentialsOAuthFlow;
use Orisai\OpenAPI\Spec\ImplicitOAuthFlow;
use Orisai\OpenAPI\Spec\OAuthFlows;
use Orisai\OpenAPI\Spec\PasswordOAuthFlow;
use PHPUnit\Framework\TestCase;

final class OAuthFlowsTest extends TestCase
{

	public function test(): void
	{
		$f1 = new OAuthFlows();
		self::assertSame([], $f1->toArray());

		$f2 = new OAuthFlows();
		$f2->authorizationCode = $f2ac = new AuthorizationCodeOAuthFlow(
			[],
			'https://example.com/api/oauth/dialog',
			'https://example.com/api/oauth/token',
		);
		$f2->clientCredentials = $f2cc = new ClientCredentialsOAuthFlow(
			[],
			'https://example.com/api/oauth/token',
		);
		$f2->implicit = $f2i = new ImplicitOAuthFlow(
			[],
			'https://example.com/api/oauth/dialog',
		);
		$f2->password = $f2p = new PasswordOAuthFlow(
			[],
			'https://example.com/api/oauth/token',
		);
		self::assertSame(
			[
				'implicit' => $f2i->toArray(),
				'password' => $f2p->toArray(),
				'clientCredentials' => $f2cc->toArray(),
				'authorizationCode' => $f2ac->toArray(),
			],
			$f2->toArray(),
		);
	}

}
