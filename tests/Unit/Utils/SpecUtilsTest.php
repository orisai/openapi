<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Utils;

use Orisai\OpenAPI\Spec\Contact;
use Orisai\OpenAPI\Utils\SpecUtils;
use PHPUnit\Framework\TestCase;

final class SpecUtilsTest extends TestCase
{

	public function test(): void
	{
		$c1 = new Contact();
		$c2 = new Contact();
		$c2->name = 'Example';

		self::assertSame(
			[
				'a' => [],
				5 => [
					'name' => 'Example',
				],
			],
			SpecUtils::specsToArray([
				'a' => $c1,
				5 => $c2,
			]),
		);
	}

}
