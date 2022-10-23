<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\Reference;
use PHPUnit\Framework\TestCase;

final class ReferenceTest extends TestCase
{

	public function test(): void
	{
		$r1 = new Reference('#/components/schemas/Hooman');
		self::assertSame(
			[
				'$ref' => '#/components/schemas/Hooman',
			],
			$r1->toArray(),
		);

		$r2 = new Reference('#/components/schemas/CatLord');
		$r2->summary = 'summary';
		$r2->description = 'description';
		self::assertSame(
			[
				'$ref' => '#/components/schemas/CatLord',
				'summary' => 'summary',
				'description' => 'description',
			],
			$r2->toArray(),
		);
	}

}
