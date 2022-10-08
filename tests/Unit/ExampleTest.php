<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit;

use Orisai\OpenAPI\Example;
use PHPUnit\Framework\TestCase;

final class ExampleTest extends TestCase
{

	public function test(): void
	{
		self::assertTrue(Example::exampleFunction());
	}

}
