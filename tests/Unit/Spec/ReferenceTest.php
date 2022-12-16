<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\Reference;
use PHPUnit\Framework\TestCase;

final class ReferenceTest extends TestCase
{

	public function test(): void
	{
		$r1 = new Reference('#/components/schemas/Hooman');
		self::assertSame('#/components/schemas/Hooman', $r1->getRef());
		self::assertSame(
			[
				'$ref' => '#/components/schemas/Hooman',
			],
			$r1->toRaw(),
		);

		$r2 = new Reference('#/components/schemas/CatLord');
		$r2->summary = 'summary';
		$r2->description = 'description';
		self::assertSame('#/components/schemas/CatLord', $r2->getRef());
		self::assertSame(
			[
				'$ref' => '#/components/schemas/CatLord',
				'summary' => 'summary',
				'description' => 'description',
			],
			$r2->toRaw(),
		);
	}

	public function testConstructors(): void
	{
		self::assertSame(
			[
				'$ref' => '#/components/callbacks/name',
			],
			Reference::ofCallback('name')->toRaw(),
		);

		self::assertSame(
			[
				'$ref' => '#/components/examples/name',
			],
			Reference::ofExample('name')->toRaw(),
		);

		self::assertSame(
			[
				'$ref' => '#/components/headers/name',
			],
			Reference::ofHeader('name')->toRaw(),
		);

		self::assertSame(
			[
				'$ref' => '#/components/links/name',
			],
			Reference::ofLink('name')->toRaw(),
		);

		self::assertSame(
			[
				'$ref' => '#/components/parameters/name',
			],
			Reference::ofParameter('name')->toRaw(),
		);

		self::assertSame(
			[
				'$ref' => '#/components/responses/name',
			],
			Reference::ofResponse('name')->toRaw(),
		);

		self::assertSame(
			[
				'$ref' => '#/components/requestBodies/name',
			],
			Reference::ofRequestBody('name')->toRaw(),
		);

		self::assertSame(
			[
				'$ref' => '#/components/securitySchemes/name',
			],
			Reference::ofSecurityScheme('name')->toRaw(),
		);

		self::assertSame(
			[
				'$ref' => '#/components/schemas/name',
			],
			Reference::ofSchema('name')->toRaw(),
		);
	}

}
