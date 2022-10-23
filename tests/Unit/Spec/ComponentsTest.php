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

		$c2->schemas['a'] = $c2s1 = new Schema();
		$c2->schemas['b'] = $c2s2 = new Reference('s1');

		$c2->responses['a'] = $c2r1 = new Response('r1');
		$c2->responses['b'] = $c2r2 = new Reference('r2');

		$c2->parameters['a'] = $c2p1 = new Parameter('p1', ParameterIn::path());
		$c2->parameters['b'] = $c2p2 = new Reference('p2');

		$c2->examples['a'] = $c2e1 = new Example();
		$c2->examples['b'] = $c2e2 = new Reference('e2');

		$c2->requestBodies['a'] = $c2rb1 = new RequestBody([]);
		$c2->requestBodies['b'] = $c2rb2 = new Reference('rb2');

		$c2->headers['a'] = $c2h1 = new Header();
		$c2->headers['b'] = $c2h2 = new Reference('h2');

		$c2->securitySchemes['a'] = $c2ss1 = new ApiKeySecurityScheme('key', SecuritySchemeIn::cookie());
		$c2->securitySchemes['b'] = $c2ss2 = new Reference('ss2');

		$c2->links['a'] = $c2l1 = new Link();
		$c2->links['b'] = $c2l2 = new Reference('l2');

		$c2->callbacks['a'] = $c2cb1 = new Callback();
		$c2->callbacks['b'] = $c2cb2 = new Reference('cb2');

		$c2->pathItems['a'] = $c2pi1 = new PathItem();
		$c2->pathItems['b'] = $c2pi2 = new Reference('pi2');

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
			],
			$c2->toArray(),
		);
	}

}
