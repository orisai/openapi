<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Enum;

use Orisai\OpenAPI\Enum\ParameterIn;
use Orisai\OpenAPI\Enum\ParameterStyle;
use PHPUnit\Framework\TestCase;
use ValueError;

final class ParameterInTest extends TestCase
{

	public function test(): void
	{
		self::assertSame('cookie', ParameterIn::cookie()->value);
		self::assertSame('Cookie', ParameterIn::cookie()->name);
		self::assertSame('header', ParameterIn::header()->value);
		self::assertSame('Header', ParameterIn::header()->name);
		self::assertSame('path', ParameterIn::path()->value);
		self::assertSame('Path', ParameterIn::path()->name);
		self::assertSame('query', ParameterIn::query()->value);
		self::assertSame('Query', ParameterIn::query()->name);

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

	public function testDefaultStyle(): void
	{
		self::assertSame(ParameterStyle::form(), ParameterIn::cookie()->getDefaultStyle());
		self::assertSame(ParameterStyle::simple(), ParameterIn::header()->getDefaultStyle());
		self::assertSame(ParameterStyle::simple(), ParameterIn::path()->getDefaultStyle());
		self::assertSame(ParameterStyle::form(), ParameterIn::query()->getDefaultStyle());
	}

	public function testAllowedStyles(): void
	{
		self::assertSame(
			[
				ParameterIn::cookie()->getDefaultStyle(),
			],
			ParameterIn::cookie()->getAllowedStyles(),
		);
		self::assertSame(
			[
				ParameterIn::header()->getDefaultStyle(),
			],
			ParameterIn::header()->getAllowedStyles(),
		);
		self::assertSame(
			[
				ParameterIn::path()->getDefaultStyle(),
				ParameterStyle::label(),
				ParameterStyle::matrix(),
			],
			ParameterIn::path()->getAllowedStyles(),
		);
		self::assertSame(
			[
				ParameterIn::query()->getDefaultStyle(),
				ParameterStyle::spaceDelimited(),
				ParameterStyle::pipeDelimited(),
				ParameterStyle::deepObject(),
			],
			ParameterIn::query()->getAllowedStyles(),
		);
	}

}
