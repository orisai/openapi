<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\Encoding;
use Orisai\OpenAPI\Spec\Header;
use Orisai\OpenAPI\Spec\Reference;
use PHPUnit\Framework\TestCase;

final class EncodingTest extends TestCase
{

	public function test(): void
	{
		$e1 = new Encoding();
		self::assertSame([], $e1->toArray());

		$e2 = new Encoding();
		$e2->contentType = 'application/json';

		$e2->headers['foo'] = $e2h1 = new Header();
		$e2h1->example = null;
		$e2->headers['bar'] = $e2h2 = new Reference('ref');

		$e2->style = 'style';
		$e2->explode = true;
		$e2->allowReserved = true;
		$e2->addExtension('x-a', null);

		self::assertSame(
			[
				'contentType' => 'application/json',
				'headers' => [
					'foo' => $e2h1->toArray(),
					'bar' => $e2h2->toArray(),
				],
				'style' => 'style',
				'explode' => true,
				'allowReserved' => true,
				'x-a' => null,
			],
			$e2->toArray(),
		);
	}

}
