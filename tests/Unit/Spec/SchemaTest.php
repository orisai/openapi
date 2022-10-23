<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\Discriminator;
use Orisai\OpenAPI\Spec\ExternalDocumentation;
use Orisai\OpenAPI\Spec\Schema;
use PHPUnit\Framework\TestCase;

final class SchemaTest extends TestCase
{

	public function test(): void
	{
		$s1 = new Schema();
		self::assertSame([], $s1->toArray());

		$s2 = new Schema();
		$d2 = new Discriminator('property');
		$s2->discriminator = $d2;
		$x2 = $s2->xml;
		$x2->name = 'name';

		$ed2 = new ExternalDocumentation('https://example.com');
		$s2->externalDocs = $ed2;

		$s2->example = null;

		self::assertSame(
			[
				'discriminator' => $d2->toArray(),
				'xml' => $x2->toArray(),
				'externalDocs' => $ed2->toArray(),
				'example' => null,
			],
			$s2->toArray(),
		);
	}

}
