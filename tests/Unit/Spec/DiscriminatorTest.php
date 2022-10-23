<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\Discriminator;
use PHPUnit\Framework\TestCase;

final class DiscriminatorTest extends TestCase
{

	public function test(): void
	{
		$d1 = new Discriminator('property');
		self::assertSame(
			[
				'propertyName' => 'property',
			],
			$d1->toArray(),
		);

		$d2 = new Discriminator('property');
		$d2->mapping['foo'] = 'bar';
		self::assertSame(
			[
				'propertyName' => 'property',
				'mapping' => [
					'foo' => 'bar',
				],
			],
			$d2->toArray(),
		);
	}

}
