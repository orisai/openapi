<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Enum;

use Orisai\OpenAPI\Enum\HeaderStyle;
use PHPUnit\Framework\TestCase;
use ValueError;

final class HeaderStyleTest extends TestCase
{

	public function test(): void
	{
		self::assertSame('simple', HeaderStyle::simple()->value);
		self::assertSame('Simple', HeaderStyle::simple()->name);

		self::assertSame(
			[
				HeaderStyle::simple(),
			],
			HeaderStyle::cases(),
		);

		self::assertSame(HeaderStyle::simple(), HeaderStyle::from('simple'));
		self::assertSame(HeaderStyle::simple(), HeaderStyle::tryFrom('simple'));

		self::assertNull(HeaderStyle::tryFrom('invalid'));
		$this->expectException(ValueError::class);
		HeaderStyle::from('invalid');
	}

	public function testDefaultExplode(): void
	{
		foreach (HeaderStyle::cases() as $case) {
			self::assertFalse($case->getDefaultExplode());
		}
	}

}
