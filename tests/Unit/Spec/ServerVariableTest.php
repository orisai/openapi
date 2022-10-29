<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\OpenAPI\Spec\ServerVariable;
use PHPUnit\Framework\TestCase;

final class ServerVariableTest extends TestCase
{

	public function test(): void
	{
		$v1 = new ServerVariable('default');
		self::assertSame(
			[
				'default' => 'default',
			],
			$v1->toArray(),
		);

		$v2 = new ServerVariable('a', ['a', 'b', 'c']);
		$v2->description = 'description';
		$v2->addExtension('x-a', null);
		self::assertSame(
			[
				'default' => 'a',
				'enum' => ['a', 'b', 'c'],
				'description' => 'description',
				'x-a' => null,
			],
			$v2->toArray(),
		);
	}

	public function testDefaultNotInEnum(): void
	{
		$this->expectException(InvalidArgument::class);
		$this->expectExceptionMessage(<<<'MSG'
Context: Creating a ServerVariable with default value 'default'.
Problem: Default variable is not listed in given enum cases 'a', 'b', 'c'.
Solution: Add default to enum or don't use enum.
MSG);

		new ServerVariable('default', ['a', 'b', 'c']);
	}

}
