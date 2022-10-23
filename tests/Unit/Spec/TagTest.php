<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\ExternalDocumentation;
use Orisai\OpenAPI\Spec\Tag;
use PHPUnit\Framework\TestCase;

final class TagTest extends TestCase
{

	public function test(): void
	{
		$t1 = new Tag('name');
		self::assertSame(
			[
				'name' => 'name',
			],
			$t1->toArray(),
		);

		$t2 = new Tag('name');
		$t2->description = 'description';
		$t2->externalDocs = new ExternalDocumentation('https://example.com');
		$t2->addExtension('x-a', null);
		self::assertSame(
			[
				'name' => 'name',
				'description' => 'description',
				'externalDocs' => [
					'url' => 'https://example.com',
				],
				'x-a' => null,
			],
			$t2->toArray(),
		);
	}

}
