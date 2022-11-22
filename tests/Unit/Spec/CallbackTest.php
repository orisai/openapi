<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\OpenAPI\Spec\Callback;
use Orisai\OpenAPI\Spec\PathItem;
use Orisai\OpenAPI\Spec\Reference;
use PHPUnit\Framework\TestCase;

final class CallbackTest extends TestCase
{

	public function test(): void
	{
		$cb1 = new Callback();
		self::assertSame([], $cb1->getExpressions());
		self::assertSame([], $cb1->toArray());

		$cb2 = new Callback();

		$cb2pi1 = new PathItem();
		$cb2pi1->description = 'description';
		$cb2->addExpression('/foo', $cb2pi1);

		$cb2pi2 = new Reference('ref');
		$cb2->addExpression('/bar', $cb2pi2);

		$cb2->addExtension('x-a', null);

		self::assertSame(
			[
				'/foo' => $cb2pi1,
				'/bar' => $cb2pi2,
			],
			$cb2->getExpressions(),
		);
		self::assertSame(
			[
				'/foo' => $cb2pi1->toArray(),
				'/bar' => $cb2pi2->toArray(),
				'x-a' => null,
			],
			$cb2->toArray(),
		);
	}

	public function testExpressionExtensionCollision(): void
	{
		$cb = new Callback();

		$this->expectException(InvalidArgument::class);
		$this->expectExceptionMessage(<<<'MSG'
Context: Adding an expression 'x-a'.
Problem: Expression cannot start with 'x-' as it collides with extension names.
MSG);

		$cb->addExpression('x-a', new Reference('ref'));
	}

}
