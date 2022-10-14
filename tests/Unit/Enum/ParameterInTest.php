<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Enum;

use Orisai\OpenAPI\Enum\ParameterIn;
use PHPUnit\Framework\TestCase;
use ValueError;

final class ParameterInTest extends TestCase
{

	public function test(): void
	{
		self::assertSame('cookie', ParameterIn::cookie()->value);
		self::assertSame('cookie', ParameterIn::cookie()->name);
		self::assertSame('header', ParameterIn::header()->value);
		self::assertSame('header', ParameterIn::header()->name);
		self::assertSame('path', ParameterIn::path()->value);
		self::assertSame('path', ParameterIn::path()->name);
		self::assertSame('query', ParameterIn::query()->value);
		self::assertSame('query', ParameterIn::query()->name);

		self::assertSame(
			[
				ParameterIn::cookie(),
				ParameterIn::header(),
				ParameterIn::path(),
				ParameterIn::query(),
			],
			ParameterIn::cases(),
		);

		self::assertSame(ParameterIn::cookie(), ParameterIn::from('cookie'));
		self::assertSame(ParameterIn::cookie(), ParameterIn::tryFrom('cookie'));

		self::assertNull(ParameterIn::tryFrom('invalid'));
		$this->expectException(ValueError::class);
		ParameterIn::from('invalid');
	}

}
