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
		self::assertSame([], $op1->getTags());
		self::assertSame([], $op1->getParameters());
		self::assertSame([], $op1->toArray());

		$op2 = new Operation();

		$op2->addTag('foo');
		$op2->addTag('foo');
		$op2->addTag('bar');
		self::assertSame(['foo', 'bar'], $op2->getTags());

		$op2->summary = 'summary';
		$op2->description = 'description';
		$op2->externalDocs = $op2ed = new ExternalDocumentation('https://example.com');
		$op2->operationId = 'operationId';

		$op2->addParameter($op2p1 = new Parameter('p1', ParameterIn::path()));
		$op2->addParameter($op2p2 = new Reference('p2'));
		self::assertSame([$op2p1, $op2p2], $op2->getParameters());

		$op2->requestBody = $op2rb = new RequestBody();
		$op2->responses->addResponse(204, new Response('no content'));

		$op2->addCallback('a', $op2cb1 = new Callback());
		$op2cb1->addExpression('a', new PathItem());
		$op2->addCallback('b', $op2cb2 = new Reference('cb2'));
		self::assertSame(
			[
				'a' => $op2cb1,
				'b' => $op2cb2,
			],
			$op2->getCallbacks(),
		);

		$op2->deprecated = true;

		$op2->addSecurity($op2sr1 = SecurityRequirement::create('api_key'));
		$op2->addSecurity($op2sr1);
		$op2->addSecurity($op2sr2 = SecurityRequirement::create('petstore_auth', ['foo']));
		self::assertSame(
			[$op2sr1, $op2sr2],
			$op2->getSecurityRequirements(),
		);

		$op2->addServer($op2s1 = new Server('https://example.com'));
		$op2->addServer($op2s1);
		$op2->addServer($op2s2 = new Server('https://example2.com'));
		self::assertSame(
			[$op2s1, $op2s2],
			$op2->getServers(),
		);

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
					'a' => $op2cb1->toArray(),
					'b' => $op2cb2->toArray(),
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

	public function testOptionalSecurityRequirementIsNotDuplicated(): void
	{
		$op = new Operation();

		$op->addSecurity(SecurityRequirement::createOptional());
		$op->addSecurity(SecurityRequirement::createOptional());

		self::assertEquals(
			[
				'security' => SecurityRequirement::createOptional()->toArray(),
			],
			$op->toArray(),
		);
	}

}
