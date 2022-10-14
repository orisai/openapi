<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Enum;

use Orisai\OpenAPI\Enum\SecuritySchemeType;
use PHPUnit\Framework\TestCase;
use ValueError;

final class SecuritySchemeTypeTest extends TestCase
{

	public function test(): void
	{
		self::assertSame('apiKey', SecuritySchemeType::apiKey()->value);
		self::assertSame('apiKey', SecuritySchemeType::apiKey()->name);
		self::assertSame('http', SecuritySchemeType::http()->value);
		self::assertSame('http', SecuritySchemeType::http()->name);
		self::assertSame('mutualTLS', SecuritySchemeType::mutualTLS()->value);
		self::assertSame('mutualTLS', SecuritySchemeType::mutualTLS()->name);
		self::assertSame('oauth2', SecuritySchemeType::oAuth2()->value);
		self::assertSame('oauth2', SecuritySchemeType::oAuth2()->name);
		self::assertSame('openIdConnect', SecuritySchemeType::openIdConnect()->value);
		self::assertSame('openIdConnect', SecuritySchemeType::openIdConnect()->name);

		self::assertSame(
			[
				SecuritySchemeType::apiKey(),
				SecuritySchemeType::http(),
				SecuritySchemeType::mutualTLS(),
				SecuritySchemeType::oAuth2(),
				SecuritySchemeType::openIdConnect(),
			],
			SecuritySchemeType::cases(),
		);

		self::assertSame(SecuritySchemeType::apiKey(), SecuritySchemeType::from('apiKey'));
		self::assertSame(SecuritySchemeType::apiKey(), SecuritySchemeType::tryFrom('apiKey'));

		self::assertNull(SecuritySchemeType::tryFrom('invalid'));
		$this->expectException(ValueError::class);
		SecuritySchemeType::from('invalid');
	}

}
