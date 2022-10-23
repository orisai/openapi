<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\Example;
use Orisai\OpenAPI\Spec\Header;
use Orisai\OpenAPI\Spec\MediaType;
use Orisai\OpenAPI\Spec\Reference;
use PHPUnit\Framework\TestCase;

final class HeaderTest extends TestCase
{

	public function test(): void
	{
		$h1 = new Header();
		self::assertSame([], $h1->toArray());

		$h2 = new Header();
		$h2->description = 'description';
		$h2->required = true;
		$h2->deprecated = true;
		$h2->allowEmptyValue = true;
		$h2->style = 'style';
		$h2->explode = true;
		$h2->allowReserved = true;
		$h2->example = null;
		$h2->schema->example = 'schema';

		$h2->examples['foo'] = $h2e1 = new Example();
		$h2e1->description = 'desc';
		$h2->examples['bar'] = $h2e2 = new Reference('ref');

		$h2->content['application/json'] = $h2c1 = new MediaType();
		$h2c1->example = 'example';
		$h2->content['application/xml'] = $h2c2 = new MediaType();

		self::assertSame(
			[
				'description' => 'description',
				'required' => true,
				'deprecated' => true,
				'allowEmptyValue' => true,
				'style' => 'style',
				'explode' => true,
				'allowReserved' => true,
				'schema' => $h2->schema->toArray(),
				'example' => null,
				'examples' => [
					'foo' => $h2e1->toArray(),
					'bar' => $h2e2->toArray(),
				],
				'content' => [
					'application/json' => $h2c1->toArray(),
					'application/xml' => $h2c2->toArray(),
				],
			],
			$h2->toArray(),
		);
	}

}
