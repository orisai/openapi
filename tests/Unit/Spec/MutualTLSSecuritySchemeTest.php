<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\MutualTLSSecurityScheme;
use PHPUnit\Framework\TestCase;

final class MutualTLSSecuritySchemeTest extends TestCase
{

	public function test(): void
	{
		$s1 = new MutualTLSSecurityScheme();
		self::assertSame(
			[
				'type' => 'mutualTLS',
			],
			$s1->toArray(),
		);

		$s2 = new MutualTLSSecurityScheme();
		$s2->description = 'description';
		self::assertSame(
			[
				'type' => 'mutualTLS',
				'description' => 'description',
			],
			$s2->toArray(),
		);
	}

}
