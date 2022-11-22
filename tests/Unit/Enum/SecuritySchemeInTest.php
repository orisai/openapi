<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Enum;

use Orisai\OpenAPI\Enum\SecuritySchemeIn;
use PHPUnit\Framework\TestCase;
use ValueError;

final class SecuritySchemeInTest extends TestCase
{

	public function test(): void
	{
		self::assertSame('cookie', SecuritySchemeIn::cookie()->value);
		self::assertSame('Cookie', SecuritySchemeIn::cookie()->name);
		self::assertSame('header', SecuritySchemeIn::header()->value);
		self::assertSame('Header', SecuritySchemeIn::header()->name);
		self::assertSame('query', SecuritySchemeIn::query()->value);
		self::assertSame('Query', SecuritySchemeIn::query()->name);

		self::assertSame(
			[
				SecuritySchemeIn::cookie(),
				SecuritySchemeIn::header(),
				SecuritySchemeIn::query(),
			],
			SecuritySchemeIn::cases(),
		);

		self::assertSame(SecuritySchemeIn::cookie(), SecuritySchemeIn::from('cookie'));
		self::assertSame(SecuritySchemeIn::cookie(), SecuritySchemeIn::tryFrom('cookie'));

		self::assertNull(SecuritySchemeIn::tryFrom('invalid'));
		$this->expectException(ValueError::class);
		SecuritySchemeIn::from('invalid');
	}

}
