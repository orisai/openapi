<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Validation;

use Orisai\OpenAPI\Validation\ValidationNode;
use PHPUnit\Framework\TestCase;

final class ValidationNodeTest extends TestCase
{

	public function test(): void
	{
		$n = new ValidationNode();
		self::assertSame([], $n->getAllErrors());
		self::assertSame([], $n->getAllWarnings());

		$n->addError('e1');
		$n->addError('e2');
		$n->addError('e2');
		$n->addWarning('w1');
		$n->addWarning('w2');
		$n->addWarning('w2');

		self::assertSame(
			[
				'e1',
				'e2',
				'e2',
			],
			$n->getAllErrors(),
		);
		self::assertSame(
			[
				'w1',
				'w2',
				'w2',
			],
			$n->getAllWarnings(),
		);

		$n->getSubNode('a')->addError('e1');
		$n->getSubNode('a')->addError('e2');
		$n->getSubNode(1)->addError('e2');
		$n->getSubNode('b')->getSubNode(2)->addError('e1');

		$n->getSubNode('a')->addWarning('w1');
		$n->getSubNode('a')->addWarning('w2');
		$n->getSubNode(1)->addWarning('w2');
		$n->getSubNode('b')->getSubNode(2)->addWarning('w1');

		self::assertSame(
			[
				'e1',
				'e2',
				'e2',
				'a: e1',
				'a: e2',
				'1: e2',
				'b > 2: e1',
			],
			$n->getAllErrors(),
		);
		self::assertSame(
			[
				'w1',
				'w2',
				'w2',
				'a: w1',
				'a: w2',
				'1: w2',
				'b > 2: w1',
			],
			$n->getAllWarnings(),
		);
	}

}
