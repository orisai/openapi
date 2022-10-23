<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Enum\ParameterIn;
use Orisai\OpenAPI\Spec\Example;
use Orisai\OpenAPI\Spec\MediaType;
use Orisai\OpenAPI\Spec\Parameter;
use Orisai\OpenAPI\Spec\Reference;
use PHPUnit\Framework\TestCase;

final class ParameterTest extends TestCase
{

	public function test(): void
	{
		$p1 = new Parameter('p1', ParameterIn::query());
		self::assertSame(
			[
				'name' => 'p1',
				'in' => 'query',
			],
			$p1->toArray(),
		);

		$p2 = new Parameter('p2', ParameterIn::path());
		self::assertSame(
			[
				'name' => 'p2',
				'in' => 'path',
				'required' => true,
			],
			$p2->toArray(),
		);

		$p3 = new Parameter('p3', ParameterIn::cookie());
		$p3->description = 'description';
		$p3->required = true;
		$p3->deprecated = true;
		$p3->allowEmptyValue = true;
		$p3->style = 'style';
		$p3->explode = true;
		$p3->allowReserved = true;
		$p3->schema->example = null;
		$p3->example = null;

		$p3->examples['foo'] = $p3e1 = new Example();
		$p3e1->description = 'desc';
		$p3->examples['bar'] = $p3e2 = new Reference('ref');

		$p3->content['application/json'] = $p3c1 = new MediaType();
		$p3c1->example = 'example';
		$p3->content['application/xml'] = $p3c2 = new MediaType();

		self::assertSame(
			[
				'name' => 'p3',
				'in' => 'cookie',
				'description' => 'description',
				'required' => true,
				'deprecated' => true,
				'allowEmptyValue' => true,
				'style' => 'style',
				'explode' => true,
				'allowReserved' => true,
				'schema' => $p3->schema->toArray(),
				'example' => null,
				'examples' => [
					'foo' => $p3e1->toArray(),
					'bar' => $p3e2->toArray(),
				],
				'content' => [
					'application/json' => $p3c1->toArray(),
					'application/xml' => $p3c2->toArray(),
				],
			],
			$p3->toArray(),
		);
	}

}
