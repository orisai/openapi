<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\OpenAPI\Spec\PathItem;
use Orisai\OpenAPI\Spec\Paths;
use PHPUnit\Framework\TestCase;

final class PathsTest extends TestCase
{

	public function test(): void
	{
		$ps1 = new Paths();
		self::assertSame([], $ps1->toRaw());

		$ps2 = new Paths();

		$ps2p1 = new PathItem();
		$ps2p1->description = 'description';
		$ps2->addPath('/foo', $ps2p1);

		$ps2p2 = new PathItem();
		$ps2->addPath('/bar', $ps2p2);

		self::assertSame(
			[
				'/foo' => $ps2p1,
				'/bar' => $ps2p2,
			],
			$ps2->getPaths(),
		);

		$ps2->addExtension('x-a', null);

		self::assertSame(
			[
				'/foo' => $ps2p1->toRaw(),
				'/bar' => $ps2p2->toRaw(),
				'x-a' => null,
			],
			$ps2->toRaw(),
		);
	}

	public function testPathWithoutLeadingSlash(): void
	{
		$ps = new Paths();

		$this->expectException(InvalidArgument::class);
		$this->expectExceptionMessage(<<<'MSG'
Context: Adding path 'foo'.
Problem: Path musts start with '/'.
MSG);

		$ps->addPath('foo', new PathItem());
	}

}
