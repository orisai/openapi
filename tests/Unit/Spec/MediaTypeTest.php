<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\Encoding;
use Orisai\OpenAPI\Spec\Example;
use Orisai\OpenAPI\Spec\MediaType;
use Orisai\OpenAPI\Spec\Reference;
use PHPUnit\Framework\TestCase;

final class MediaTypeTest extends TestCase
{

	public function test(): void
	{
		$mt1 = new MediaType();
		self::assertSame([], $mt1->toArray());

		$mt2 = new MediaType();

		$mt2s = $mt2->schema;
		$mt2s->example = null;

		$mt2->example = null;

		$mt2->examples['foo'] = $h2ex1 = new Example();
		$h2ex1->description = 'desc';
		$mt2->examples['bar'] = $h2ex2 = new Reference('ref');

		$mt2->encoding['foo'] = $h2en1 = new Encoding();
		$h2en1->style = 'style';
		$mt2->encoding['bar'] = $h2en2 = new Reference('ref');

		self::assertSame(
			[
				'schema' => $mt2s->toArray(),
				'example' => null,
				'examples' => [
					'foo' => $h2ex1->toArray(),
					'bar' => $h2ex2->toArray(),
				],
				'encoding' => [
					'foo' => $h2en1->toArray(),
					'bar' => $h2en2->toArray(),
				],
			],
			$mt2->toArray(),
		);
	}

}
