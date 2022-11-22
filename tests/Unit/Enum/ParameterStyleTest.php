<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Enum;

use Orisai\OpenAPI\Enum\ParameterStyle;
use PHPUnit\Framework\TestCase;
use ValueError;

final class ParameterStyleTest extends TestCase
{

	public function test(): void
	{
		self::assertSame('deepObject', ParameterStyle::deepObject()->value);
		self::assertSame('DeepObject', ParameterStyle::deepObject()->name);
		self::assertSame('form', ParameterStyle::form()->value);
		self::assertSame('Form', ParameterStyle::form()->name);
		self::assertSame('label', ParameterStyle::label()->value);
		self::assertSame('Label', ParameterStyle::label()->name);
		self::assertSame('matrix', ParameterStyle::matrix()->value);
		self::assertSame('Matrix', ParameterStyle::matrix()->name);
		self::assertSame('pipeDelimited', ParameterStyle::pipeDelimited()->value);
		self::assertSame('PipeDelimited', ParameterStyle::pipeDelimited()->name);
		self::assertSame('simple', ParameterStyle::simple()->value);
		self::assertSame('Simple', ParameterStyle::simple()->name);
		self::assertSame('spaceDelimited', ParameterStyle::spaceDelimited()->value);
		self::assertSame('SpaceDelimited', ParameterStyle::spaceDelimited()->name);

		self::assertSame(
			[
				ParameterStyle::deepObject(),
				ParameterStyle::form(),
				ParameterStyle::label(),
				ParameterStyle::matrix(),
				ParameterStyle::pipeDelimited(),
				ParameterStyle::simple(),
				ParameterStyle::spaceDelimited(),
			],
			ParameterStyle::cases(),
		);

		self::assertSame(ParameterStyle::deepObject(), ParameterStyle::from('deepObject'));
		self::assertSame(ParameterStyle::deepObject(), ParameterStyle::tryFrom('deepObject'));

		self::assertNull(ParameterStyle::tryFrom('invalid'));
		$this->expectException(ValueError::class);
		ParameterStyle::from('invalid');
	}

	public function testDefaultExplode(): void
	{
		foreach (ParameterStyle::cases() as $case) {
			self::assertSame(
				$case === ParameterStyle::form(),
				$case->getDefaultExplode(),
			);
		}
	}

}
