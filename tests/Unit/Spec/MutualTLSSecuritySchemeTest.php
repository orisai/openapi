<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Enum\SecuritySchemeType;
use Orisai\OpenAPI\Spec\MutualTLSSecurityScheme;
use PHPUnit\Framework\TestCase;

final class MutualTLSSecuritySchemeTest extends TestCase
{

	public function test(): void
	{
		$s1 = new MutualTLSSecurityScheme();
		self::assertSame(SecuritySchemeType::mutualTLS(), $s1->getType());
		self::assertSame(
			[
				'type' => 'mutualTLS',
			],
			$s1->toRaw(),
		);

		$s2 = new MutualTLSSecurityScheme();
		$s2->description = 'description';
		$s2->addExtension('x-a', null);
		self::assertSame(
			[
				'type' => 'mutualTLS',
				'description' => 'description',
				'x-a' => null,
			],
			$s2->toRaw(),
		);
	}

}
