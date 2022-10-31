<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\SecurityRequirement;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Yaml\Yaml;
use function json_encode;
use const JSON_PRETTY_PRINT;

final class SecurityRequirementTest extends TestCase
{

	public function test(): void
	{
		$sr1 = SecurityRequirement::createOptional();
		self::assertNull($sr1->getName());
		self::assertSame([], $sr1->getScopes());
		self::assertEquals([new stdClass()], $sr1->toArray());

		$sr2 = SecurityRequirement::create('api_key');
		self::assertSame('api_key', $sr2->getName());
		self::assertSame([], $sr2->getScopes());
		self::assertSame(['api_key' => []], $sr2->toArray());

		$sr3 = SecurityRequirement::create('petstore_auth', [
			'write:pets',
			'read:pets',
		]);
		self::assertSame('petstore_auth', $sr3->getName());
		self::assertSame(
			[
				'write:pets',
				'read:pets',
			],
			$sr3->getScopes(),
		);
		self::assertSame(
			[
				'petstore_auth' => [
					'write:pets',
					'read:pets',
				],
			],
			$sr3->toArray(),
		);
	}

	public function testEncodeEmptyRequirementsAsObject(): void
	{
		$sr = SecurityRequirement::createOptional();

		self::assertEquals(
			[new stdClass()],
			$sr->toArray(),
		);

		self::assertSame(
			<<<'JSON'
[
    {}
]
JSON,
			json_encode($sr->toArray(), JSON_PRETTY_PRINT),
		);

		self::assertSame(
			<<<'YAML'
- {  }

YAML,
			Yaml::dump($sr->toArray(), 2, 4, Yaml::DUMP_OBJECT_AS_MAP | Yaml::DUMP_EMPTY_ARRAY_AS_SEQUENCE),
		);
	}

}
