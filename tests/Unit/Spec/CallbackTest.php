<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\Callback;
use Orisai\OpenAPI\Spec\PathItem;
use Orisai\OpenAPI\Spec\Reference;
use PHPUnit\Framework\TestCase;

final class CallbackTest extends TestCase
{

	public function test(): void
	{
		$cb1 = new Callback();
		self::assertSame([], $cb1->toArray());

		$cb2 = new Callback();

		$cb2pi1 = new PathItem();
		$cb2pi1->description = 'description';
		$cb2->expressions['/foo'] = $cb2pi1;

		$cb2pi2 = new Reference('ref');
		$cb2->expressions['/bar'] = $cb2pi2;

		self::assertSame(
			[
				'/foo' => $cb2pi1->toArray(),
				'/bar' => $cb2pi2->toArray(),
			],
			$cb2->toArray(),
		);
	}

}
