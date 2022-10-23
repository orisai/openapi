<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\Contact;
use PHPUnit\Framework\TestCase;

final class ContactTest extends TestCase
{

	public function test(): void
	{
		$c1 = new Contact();
		self::assertSame([], $c1->toArray());

		$c2 = new Contact();
		$c2->name = 'Example';
		$c2->url = 'https://example.com';
		$c2->email = 'example@example.com';
		$c2->addExtension('x-a', null);
		self::assertSame(
			[
				'name' => 'Example',
				'url' => 'https://example.com',
				'email' => 'example@example.com',
				'x-a' => null,
			],
			$c2->toArray(),
		);
	}

}
