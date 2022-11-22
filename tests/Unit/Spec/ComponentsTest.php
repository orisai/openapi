<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Closure;
use Generator;
use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\OpenAPI\Enum\ParameterIn;
use Orisai\OpenAPI\Enum\SecuritySchemeIn;
use Orisai\OpenAPI\Spec\ApiKeySecurityScheme;
use Orisai\OpenAPI\Spec\Callback;
use Orisai\OpenAPI\Spec\Components;
use Orisai\OpenAPI\Spec\Example;
use Orisai\OpenAPI\Spec\Header;
use Orisai\OpenAPI\Spec\Link;
use Orisai\OpenAPI\Spec\NullSchema;
use Orisai\OpenAPI\Spec\Parameter;
use Orisai\OpenAPI\Spec\PathItem;
use Orisai\OpenAPI\Spec\Reference;
use Orisai\OpenAPI\Spec\RequestBody;
use Orisai\OpenAPI\Spec\Response;
use PHPUnit\Framework\TestCase;

final class ComponentsTest extends TestCase
{

	public function test(): void
	{
		$c1 = new Components();
		self::assertSame([], $c1->getSchemas());
		self::assertSame([], $c1->getResponses());
		self::assertSame([], $c1->getParameters());
		self::assertSame([], $c1->getExamples());
		self::assertSame([], $c1->getRequestBodies());
		self::assertSame([], $c1->getHeaders());
		self::assertSame([], $c1->getSecuritySchemes());
		self::assertSame([], $c1->getLinks());
		self::assertSame([], $c1->getCallbacks());
		self::assertSame([], $c1->getPathItems());
		self::assertSame([], $c1->toArray());

		$c2 = new Components();

		$c2->addSchema('a', $c2s1 = new NullSchema());
		$c2->addSchema('b', $c2s2 = new Reference('s1'));
		self::assertSame(
			[
				'a' => $c2s1,
				'b' => $c2s2,
			],
			$c2->getSchemas(),
		);

		$c2->addResponse('a', $c2r1 = new Response('r1'));
		$c2->addResponse('b', $c2r2 = new Reference('r2'));
		self::assertSame(
			[
				'a' => $c2r1,
				'b' => $c2r2,
			],
			$c2->getResponses(),
		);

		$c2->addParameter('a', $c2p1 = new Parameter('p1', ParameterIn::path()));
		$c2->addParameter('b', $c2p2 = new Reference('p2'));
		self::assertSame(
			[
				'a' => $c2p1,
				'b' => $c2p2,
			],
			$c2->getParameters(),
		);

		$c2->addExample('a', $c2e1 = new Example());
		$c2->addExample('b', $c2e2 = new Reference('e2'));
		self::assertSame(
			[
				'a' => $c2e1,
				'b' => $c2e2,
			],
			$c2->getExamples(),
		);

		$c2->addRequestBody('a', $c2rb1 = new RequestBody());
		$c2->addRequestBody('b', $c2rb2 = new Reference('rb2'));
		self::assertSame(
			[
				'a' => $c2rb1,
				'b' => $c2rb2,
			],
			$c2->getRequestBodies(),
		);

		$c2->addHeader('a', $c2h1 = new Header());
		$c2->addHeader('b', $c2h2 = new Reference('h2'));
		self::assertSame(
			[
				'a' => $c2h1,
				'b' => $c2h2,
			],
			$c2->getHeaders(),
		);

		$c2->addSecurityScheme('a', $c2ss1 = new ApiKeySecurityScheme('key', SecuritySchemeIn::cookie()));
		$c2->addSecurityScheme('b', $c2ss2 = new Reference('ss2'));
		self::assertSame(
			[
				'a' => $c2ss1,
				'b' => $c2ss2,
			],
			$c2->getSecuritySchemes(),
		);

		$c2->addLink('a', $c2l1 = Link::forId('id1'));
		$c2->addLink('b', $c2l2 = new Reference('l2'));
		self::assertSame(
			[
				'a' => $c2l1,
				'b' => $c2l2,
			],
			$c2->getLinks(),
		);

		$c2->addCallback('a', $c2cb1 = new Callback());
		$c2cb1->addExpression('a', new PathItem());
		$c2->addCallback('b', $c2cb2 = new Reference('cb2'));
		self::assertSame(
			[
				'a' => $c2cb1,
				'b' => $c2cb2,
			],
			$c2->getCallbacks(),
		);

		$c2->addPathItem('a', $c2pi1 = new PathItem());
		$c2->addPathItem('b', $c2pi2 = new Reference('pi2'));
		self::assertSame(
			[
				'a' => $c2pi1,
				'b' => $c2pi2,
			],
			$c2->getPathItems(),
		);

		$c2->addExtension('x-a', null);

		self::assertSame(
			[
				'schemas' => [
					'a' => $c2s1->toArray(),
					'b' => $c2s2->toArray(),
				],
				'responses' => [
					'a' => $c2r1->toArray(),
					'b' => $c2r2->toArray(),
				],
				'parameters' => [
					'a' => $c2p1->toArray(),
					'b' => $c2p2->toArray(),
				],
				'examples' => [
					'a' => $c2e1->toArray(),
					'b' => $c2e2->toArray(),
				],
				'requestBodies' => [
					'a' => $c2rb1->toArray(),
					'b' => $c2rb2->toArray(),
				],
				'headers' => [
					'a' => $c2h1->toArray(),
					'b' => $c2h2->toArray(),
				],
				'securitySchemes' => [
					'a' => $c2ss1->toArray(),
					'b' => $c2ss2->toArray(),
				],
				'links' => [
					'a' => $c2l1->toArray(),
					'b' => $c2l2->toArray(),
				],
				'callbacks' => [
					'a' => $c2cb1->toArray(),
					'b' => $c2cb2->toArray(),
				],
				'pathItems' => [
					'a' => $c2pi1->toArray(),
					'b' => $c2pi2->toArray(),
				],
				'x-a' => null,
			],
			$c2->toArray(),
		);
	}

	public function testValidName(): void
	{
		$c = new Components();
		$key = 'azAZ09.-';
		$ref = new Reference('ref');

		$c->addSchema($key, $ref);
		$c->addResponse($key, $ref);
		$c->addParameter($key, $ref);
		$c->addExample($key, $ref);
		$c->addRequestBody($key, $ref);
		$c->addHeader($key, $ref);
		$c->addSecurityScheme($key, $ref);
		$c->addLink($key, $ref);
		$c->addCallback($key, $ref);
		$c->addPathItem($key, $ref);

		self::assertCount(10, $c->toArray());
	}

	/**
	 * @param Closure(Components, Reference): void $add
	 *
	 * @dataProvider provideInvalidNameOfComponents
	 */
	public function testInvalidNameOfComponents(string $key, string $specType, Closure $add): void
	{
		$c = new Components();
		$ref = new Reference('ref');

		$this->expectException(InvalidArgument::class);
		$this->expectExceptionMessage(<<<MSG
Context: Assigning a spec object '$specType' with key '$key'.
Problem: Key must match regular expression '^[a-zA-Z0-9\.\-_]+$'.
MSG);

		$add($c, $ref);
	}

	public function provideInvalidNameOfComponents(): Generator
	{
		$key = 'áž';

		yield [
			$key,
			'Schema',
			static fn (Components $c, Reference $ref) => $c->addSchema($key, $ref),
		];

		yield [
			$key,
			'Response',
			static fn (Components $c, Reference $ref) => $c->addResponse($key, $ref),
		];

		yield [
			$key,
			'Parameter',
			static fn (Components $c, Reference $ref) => $c->addParameter($key, $ref),
		];

		yield [
			$key,
			'Example',
			static fn (Components $c, Reference $ref) => $c->addExample($key, $ref),
		];

		yield [
			$key,
			'Request Body',
			static fn (Components $c, Reference $ref) => $c->addRequestBody($key, $ref),
		];

		yield [
			$key,
			'Header',
			static fn (Components $c, Reference $ref) => $c->addHeader($key, $ref),
		];

		yield [
			$key,
			'Security Scheme',
			static fn (Components $c, Reference $ref) => $c->addSecurityScheme($key, $ref),
		];

		yield [
			$key,
			'Link',
			static fn (Components $c, Reference $ref) => $c->addLink($key, $ref),
		];

		yield [
			$key,
			'Callback',
			static fn (Components $c, Reference $ref) => $c->addCallback($key, $ref),
		];

		yield [
			$key,
			'Path Item',
			static fn (Components $c, Reference $ref) => $c->addPathItem($key, $ref),
		];
	}

	/**
	 * @dataProvider provideInvalidNameVariants
	 */
	public function testInvalidNameVariants(string $key): void
	{
		$c = new Components();
		$ref = new Reference('ref');

		$this->expectException(InvalidArgument::class);
		$this->expectExceptionMessage(<<<MSG
Context: Assigning a spec object 'Schema' with key '$key'.
Problem: Key must match regular expression '^[a-zA-Z0-9\.\-_]+$'.
MSG);

		$c->addSchema($key, $ref);
	}

	public function provideInvalidNameVariants(): Generator
	{
		yield ['až'];
		yield ['azAZ09.-ž'];
		yield ['žazAZ09.-'];
	}

}
