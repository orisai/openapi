<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\SecurityRequirement;
use PHPUnit\Framework\TestCase;

final class SecurityRequirementTest extends TestCase
{

	public function test(): void
	{
		$r1 = new SecurityRequirement();
		self::assertSame([], $r1->toArray());

		$r2 = new SecurityRequirement();
		$r2->requirements['api_key'] = [];
		$r2->requirements['petstore_auth'] = [
			'write:pets',
			'read:pets',
		];
		self::assertSame(
			[
				'api_key' => [],
				'petstore_auth' => [
					'write:pets',
					'read:pets',
				],
			],
			$r2->toArray(),
		);
	}

}
