<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Validation;

use Orisai\ObjectMapper\Tester\ObjectMapperTester;
use PHPUnit\Framework\TestCase;

final class MappedObjectsTest extends TestCase
{

	public function test(): void
	{
		$tester = new ObjectMapperTester();
		$deps = $tester->buildDependencies();
		$loader = $deps->metaLoader;

		$loader->preloadFromPaths([__DIR__ . '/../../../src']);

		self::assertTrue(true);
	}

}
