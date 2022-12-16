<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\ExternalDocumentation;
use PHPUnit\Framework\TestCase;

final class ExternalDocumentationTest extends TestCase
{

	public function test(): void
	{
		$d1 = new ExternalDocumentation('https://example.com');
		self::assertSame(
			[
				'url' => 'https://example.com',
			],
			$d1->toRaw(),
		);

		$d2 = new ExternalDocumentation('https://example.com');
		$d2->description = 'description';
		$d2->addExtension('x-a', null);
		self::assertSame(
			[
				'url' => 'https://example.com',
				'description' => 'description',
				'x-a' => null,
			],
			$d2->toRaw(),
		);
	}

}
