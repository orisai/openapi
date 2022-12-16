<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\License;
use PHPUnit\Framework\TestCase;

final class LicenseTest extends TestCase
{

	public function test(): void
	{
		$l1 = new License('MPL-2.0');
		self::assertSame('MPL-2.0', $l1->getName());
		self::assertNull($l1->getIdentifier());
		self::assertNull($l1->getUrl());
		self::assertSame(
			[
				'name' => 'MPL-2.0',
			],
			$l1->toRaw(),
		);

		$l2 = new License('The Unlicense', 'Unlicense', 'https://unlicense.org');
		$l2->addExtension('x-a', null);

		self::assertSame('The Unlicense', $l2->getName());
		self::assertSame('Unlicense', $l2->getIdentifier());
		self::assertSame('https://unlicense.org', $l2->getUrl());
		self::assertSame(
			[
				'name' => 'The Unlicense',
				'identifier' => 'Unlicense',
				'url' => 'https://unlicense.org',
				'x-a' => null,
			],
			$l2->toRaw(),
		);
	}

}
