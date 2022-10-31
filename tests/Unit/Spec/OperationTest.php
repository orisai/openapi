<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Enum\ParameterIn;
use Orisai\OpenAPI\Spec\Callback;
use Orisai\OpenAPI\Spec\ExternalDocumentation;
use Orisai\OpenAPI\Spec\Operation;
use Orisai\OpenAPI\Spec\Parameter;
use Orisai\OpenAPI\Spec\PathItem;
use Orisai\OpenAPI\Spec\Reference;
use Orisai\OpenAPI\Spec\RequestBody;
use Orisai\OpenAPI\Spec\Response;
use Orisai\OpenAPI\Spec\SecurityRequirement;
use Orisai\OpenAPI\Spec\Server;
use PHPUnit\Framework\TestCase;
use function array_merge;

final class OperationTest extends TestCase
{

	public function test(): void
	{
		$op1 = new Operation();
		self::assertSame([], $op1->toArray());

		$op2 = new Operation();
		$op2->tags[] = 'foo';
		$op2->tags[] = 'bar';
		$op2->summary = 'summary';
		$op2->description = 'description';
		$op2->externalDocs = $op2ed = new ExternalDocumentation('https://example.com');
		$op2->operationId = 'operationId';

		$op2->parameters[] = $op2p1 = new Parameter('p1', ParameterIn::path());
		$op2->parameters[] = $op2p2 = new Reference('p2');

		$op2->requestBody = $op2rb = new RequestBody([]);
		$op2->responses->addResponse(204, new Response('no content'));

		$op2->callbacks['foo'] = $op2cb1 = new Callback();
		$op2->callbacks['bar'] = $op2cb2 = new Callback();
		$op2cb2->addExpression('a', new PathItem());

		$op2->deprecated = true;

		$op2->security[] = $op2sr1 = SecurityRequirement::create('api_key');
		$op2->security[] = $op2sr2 = SecurityRequirement::create('petstore_auth', ['foo']);

		$op2->servers[] = $op2s1 = new Server('https://example.com');
		$op2->servers[] = $op2s2 = new Server('https://example2.com');

		$op2->addExtension('x-a', null);

		self::assertSame(
			[
				'tags' => ['foo', 'bar'],
				'summary' => 'summary',
				'description' => 'description',
				'externalDocs' => $op2ed->toArray(),
				'operationId' => 'operationId',
				'parameters' => [
					$op2p1->toArray(),
					$op2p2->toArray(),
				],
				'requestBody' => $op2rb->toArray(),
				'responses' => $op2->responses->toArray(),
				'callbacks' => [
					'foo' => $op2cb1->toArray(),
					'bar' => $op2cb2->toArray(),
				],
				'deprecated' => true,
				'security' => array_merge(
					$op2sr1->toArray(),
					$op2sr2->toArray(),
				),
				'servers' => [
					$op2s1->toArray(),
					$op2s2->toArray(),
				],
				'x-a' => null,
			],
			$op2->toArray(),
		);

		$op3 = new Operation();
		$op3->requestBody = $op3rb = new Reference('body');
		self::assertSame(
			[
				'requestBody' => $op3rb->toArray(),
			],
			$op3->toArray(),
		);
	}

}
