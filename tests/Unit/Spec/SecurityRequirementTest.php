<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\SecurityRequirement;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\Yaml\Yaml;
use function json_encode;
use function str_replace;
use const JSON_PRESERVE_ZERO_FRACTION;
use const JSON_PRETTY_PRINT;
use const JSON_THROW_ON_ERROR;
use const JSON_UNESCAPED_SLASHES;
use const JSON_UNESCAPED_UNICODE;
use const PHP_EOL;

final class SecurityRequirementTest extends TestCase
{

	public function test(): void
	{
		$sr1Pairs = [];
		$sr1 = new SecurityRequirement($sr1Pairs);
		self::assertSame($sr1Pairs, $sr1->getNameAndScopePairs());
		self::assertEquals(new stdClass(), $sr1->toRaw());

		$sr2Pairs = [
			'api_key' => [],
			'another_api_key' => ['role1', 'role2'],
		];
		$sr2 = new SecurityRequirement($sr2Pairs);
		self::assertSame($sr2Pairs, $sr2->getNameAndScopePairs());
		self::assertSame($sr2Pairs, $sr2->toRaw());

		$sr3Pairs = [
			'petstore_auth' => [
				'write:pets',
				'read:pets',
			],
		];
		$sr3 = new SecurityRequirement($sr3Pairs);
		self::assertSame($sr3Pairs, $sr3->getNameAndScopePairs());
		self::assertSame($sr3Pairs, $sr3->toRaw());
	}

	public function testEncodeEmptyRequirementsAsObject(): void
	{
		$sr = new SecurityRequirement([]);

		self::assertEquals(
			new stdClass(),
			$sr->toRaw(),
		);

		self::assertSame(
			<<<'JSON'
{}
JSON,
			str_replace(
				"\n",
				PHP_EOL,
				json_encode(
					$sr->toRaw(),
					JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR,
				),
			),
		);

		self::assertSame(
			<<<'YAML'
{  }
YAML,
			str_replace(
				"\n",
				PHP_EOL,
				Yaml::dump($sr->toRaw(), 2, 4, Yaml::DUMP_OBJECT_AS_MAP | Yaml::DUMP_EMPTY_ARRAY_AS_SEQUENCE),
			),
		);
	}

}
