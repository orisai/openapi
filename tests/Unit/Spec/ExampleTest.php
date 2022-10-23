<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\Example;
use PHPUnit\Framework\TestCase;

final class ExampleTest extends TestCase
{

	public function test(): void
	{
		$e1 = new Example();
		self::assertSame([], $e1->toArray());

		$e2 = new Example();
		$e2->summary = 'summary';
		$e2->description = 'description';
		$e2->value = null;
		$e2->externalValue = 'https://example.com/user-example.json';
		self::assertSame(
			[
				'summary' => 'summary',
				'description' => 'description',
				'value' => null,
				'externalValue' => 'https://example.com/user-example.json',
			],
			$e2->toArray(),
		);
	}

}
