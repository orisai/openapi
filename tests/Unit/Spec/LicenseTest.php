<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\License;
use PHPUnit\Framework\TestCase;

final class LicenseTest extends TestCase
{

	public function test(): void
	{
		$l1 = new License('MPL-2.0');
		self::assertSame(
			[
				'name' => 'MPL-2.0',
			],
			$l1->toArray(),
		);

		$l2 = new License('The Unlicense');
		$l2->identifier = 'Unlicense';
		$l2->url = 'https://unlicense.org';
		self::assertSame(
			[
				'name' => 'The Unlicense',
				'identifier' => 'Unlicense',
				'url' => 'https://unlicense.org',
			],
			$l2->toArray(),
		);
	}

}
