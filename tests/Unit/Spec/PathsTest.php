<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\PathItem;
use Orisai\OpenAPI\Spec\Paths;
use PHPUnit\Framework\TestCase;

final class PathsTest extends TestCase
{

	public function test(): void
	{
		$ps1 = new Paths();
		self::assertSame([], $ps1->toArray());

		$ps2 = new Paths();

		$ps2p1 = new PathItem();
		$ps2p1->description = 'description';
		$ps2->addPath('/foo', $ps2p1);

		$ps2p2 = new PathItem();
		$ps2->addPath('/bar', $ps2p2);

		$ps2->addExtension('x-a', null);

		self::assertSame(
			[
				'/foo' => $ps2p1->toArray(),
				'/bar' => $ps2p2->toArray(),
				'x-a' => null,
			],
			$ps2->toArray(),
		);
	}

}
