<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Enum;

use Orisai\OpenAPI\Enum\EncodingStyle;
use PHPUnit\Framework\TestCase;
use ValueError;

final class EncodingStyleTest extends TestCase
{

	public function test(): void
	{
		self::assertSame('deepObject', EncodingStyle::deepObject()->value);
		self::assertSame('DeepObject', EncodingStyle::deepObject()->name);
		self::assertSame('form', EncodingStyle::form()->value);
		self::assertSame('Form', EncodingStyle::form()->name);
		self::assertSame('pipeDelimited', EncodingStyle::pipeDelimited()->value);
		self::assertSame('PipeDelimited', EncodingStyle::pipeDelimited()->name);
		self::assertSame('spaceDelimited', EncodingStyle::spaceDelimited()->value);
		self::assertSame('SpaceDelimited', EncodingStyle::spaceDelimited()->name);

		self::assertSame(
			[
				EncodingStyle::deepObject(),
				EncodingStyle::form(),
				EncodingStyle::pipeDelimited(),
				EncodingStyle::spaceDelimited(),
			],
			EncodingStyle::cases(),
		);

		self::assertSame(EncodingStyle::deepObject(), EncodingStyle::from('deepObject'));
		self::assertSame(EncodingStyle::deepObject(), EncodingStyle::tryFrom('deepObject'));

		self::assertNull(EncodingStyle::tryFrom('invalid'));
		$this->expectException(ValueError::class);
		EncodingStyle::from('invalid');
	}

	public function testDefaultExplode(): void
	{
		foreach (EncodingStyle::cases() as $case) {
			self::assertSame(
				$case === EncodingStyle::form(),
				$case->getDefaultExplode(),
			);
		}
	}

}
