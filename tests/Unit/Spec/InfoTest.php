<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\Contact;
use Orisai\OpenAPI\Spec\Info;
use Orisai\OpenAPI\Spec\License;
use PHPUnit\Framework\TestCase;

final class InfoTest extends TestCase
{

	public function test(): void
	{
		$i1 = new Info('title', 'version');
		self::assertSame(
			[
				'title' => 'title',
				'version' => 'version',
			],
			$i1->toArray(),
		);

		$i2 = new Info('t', 'v');
		$i2->summary = 'summary';
		$i2->description = 'description';
		$i2->termsOfService = 'terms';
		$i2->contact = new Contact();
		$i2->license = new License('MPL-2.0');
		$i2->addExtension('x-a', null);
		self::assertSame(
			[
				'title' => 't',
				'version' => 'v',
				'summary' => 'summary',
				'description' => 'description',
				'termsOfService' => 'terms',
				'contact' => [],
				'license' => [
					'name' => 'MPL-2.0',
				],
				'x-a' => null,
			],
			$i2->toArray(),
		);
	}

}
