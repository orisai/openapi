<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\XML;
use PHPUnit\Framework\TestCase;

final class XMLTest extends TestCase
{

	public function test(): void
	{
		$x1 = new XML();
		self::assertSame([], $x1->toArray());

		$x2 = new XML();
		$x2->name = 'name';
		$x2->namespace = 'namespace';
		$x2->prefix = 'prefix';
		$x2->attribute = true;
		$x2->wrapped = true;
		self::assertSame(
			[
				'name' => 'name',
				'namespace' => 'namespace',
				'prefix' => 'prefix',
				'attribute' => true,
				'wrapped' => true,
			],
			$x2->toArray(),
		);
	}

}
