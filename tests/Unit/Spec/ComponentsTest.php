<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Enum\ParameterIn;
use Orisai\OpenAPI\Enum\SecuritySchemeIn;
use Orisai\OpenAPI\Spec\ApiKeySecurityScheme;
use Orisai\OpenAPI\Spec\Callback;
use Orisai\OpenAPI\Spec\Components;
use Orisai\OpenAPI\Spec\Example;
use Orisai\OpenAPI\Spec\Header;
use Orisai\OpenAPI\Spec\Link;
use Orisai\OpenAPI\Spec\Parameter;
use Orisai\OpenAPI\Spec\PathItem;
use Orisai\OpenAPI\Spec\Reference;
use Orisai\OpenAPI\Spec\RequestBody;
use Orisai\OpenAPI\Spec\Response;
use Orisai\OpenAPI\Spec\Schema;
use PHPUnit\Framework\TestCase;

final class ComponentsTest extends TestCase
{

	public function test(): void
	{
		$c1 = new Components();
		self::assertSame([], $c1->toArray());

		$c2 = new Components();

		$c2->addSchema('a', $c2s1 = new Schema());
		$c2->addSchema('b', $c2s2 = new Reference('s1'));

		$c2->addResponse('a', $c2r1 = new Response('r1'));
		$c2->addResponse('b', $c2r2 = new Reference('r2'));

		$c2->addParameter('a', $c2p1 = new Parameter('p1', ParameterIn::path()));
		$c2->addParameter('b', $c2p2 = new Reference('p2'));

		$c2->addExample('a', $c2e1 = new Example());
		$c2->addExample('b', $c2e2 = new Reference('e2'));

		$c2->addRequestBody('a', $c2rb1 = new RequestBody([]));
		$c2->addRequestBody('b', $c2rb2 = new Reference('rb2'));

		$c2->addHeader('a', $c2h1 = new Header());
		$c2->addHeader('b', $c2h2 = new Reference('h2'));

		$c2->addSecurityScheme('a', $c2ss1 = new ApiKeySecurityScheme('key', SecuritySchemeIn::cookie()));
		$c2->addSecurityScheme('b', $c2ss2 = new Reference('ss2'));

		$c2->addLink('a', $c2l1 = new Link());
		$c2->addLink('b', $c2l2 = new Reference('l2'));

		$c2->addCallback('a', $c2cb1 = new Callback());
		$c2->addCallback('b', $c2cb2 = new Reference('cb2'));

		$c2->addPathItem('a', $c2pi1 = new PathItem());
		$c2->addPathItem('b', $c2pi2 = new Reference('pi2'));

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

}
