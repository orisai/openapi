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
		self::assertSame([], $op1->getCallbacks());
		self::assertSame([], $op1->getParameters());
		self::assertNull($op1->getSecurity());
		self::assertSame([], $op1->getServers());
		self::assertSame([], $op1->getTags());
		self::assertSame([], $op1->toRaw());

		$op1->setNoSecurity();
		self::assertSame([], $op1->getSecurity());

		$op2 = new Operation();

		$op2->addTag('foo');
		$op2->addTag('foo');
		$op2->addTag('bar');
		$op2->addTag('123');
		self::assertSame(['foo', 'bar', '123'], $op2->getTags());

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

		$op2->addSecurity($op2sr1 = new SecurityRequirement(['api_key' => []]));
		$op2->addSecurity($op2sr1);
		$op2->addSecurity($op2sr2 = new SecurityRequirement(['petstore_auth' => ['foo']]));
		self::assertSame(
			[$op2sr1, $op2sr2],
			$op2->getSecurity(),
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
				'tags' => ['foo', 'bar', '123'],
				'summary' => 'summary',
				'description' => 'description',
				'externalDocs' => $op2ed->toRaw(),
				'operationId' => 'operationId',
				'parameters' => [
					$op2p1->toRaw(),
					$op2p2->toRaw(),
				],
				'requestBody' => $op2rb->toRaw(),
				'responses' => $op2->responses->toRaw(),
				'callbacks' => [
					'a' => $op2cb1->toRaw(),
					'b' => $op2cb2->toRaw(),
				],
				'deprecated' => true,
				'security' => array_merge(
					$op2sr1->toRaw(),
					$op2sr2->toRaw(),
				),
				'servers' => [
					$op2s1->toRaw(),
					$op2s2->toRaw(),
				],
				'x-a' => null,
			],
			$op2->toRaw(),
		);

		$op3 = new Operation();
		$op3->requestBody = $op3rb = new Reference('body');
		self::assertSame(
			[
				'requestBody' => $op3rb->toRaw(),
			],
			$op3->toRaw(),
		);
	}

}
