<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\Discriminator;
use PHPUnit\Framework\TestCase;

final class DiscriminatorTest extends TestCase
{

	public function test(): void
	{
		$d1 = new Discriminator('property');
		self::assertSame('property', $d1->getPropertyName());
		self::assertSame([], $d1->getMapping());
		self::assertSame(
			[
				'propertyName' => 'property',
			],
			$d1->toRaw(),
		);

		$d2 = new Discriminator('petType');
		self::assertSame('petType', $d2->getPropertyName());

		$d2->addMapping('cat', 'Cat');
		$d2->addMapping('dog', '#/components/schemas/Dog');
		$d2->addMapping('fennec', 'https://example.com/schemas/Fennec/schema.json');
		self::assertSame(
			[
				'cat' => 'Cat',
				'dog' => '#/components/schemas/Dog',
				'fennec' => 'https://example.com/schemas/Fennec/schema.json',
			],
			$d2->getMapping(),
		);

		$d2->addExtension('x-a', null);

		self::assertSame(
			[
				'propertyName' => 'petType',
				'mapping' => [
					'cat' => 'Cat',
					'dog' => '#/components/schemas/Dog',
					'fennec' => 'https://example.com/schemas/Fennec/schema.json',
				],
				'x-a' => null,
			],
			$d2->toRaw(),
		);
	}

}
