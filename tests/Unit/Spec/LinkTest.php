<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\Link;
use Orisai\OpenAPI\Spec\Server;
use PHPUnit\Framework\TestCase;

final class LinkTest extends TestCase
{

	public function test(): void
	{
		$l1 = new Link();
		self::assertSame([], $l1->toArray());

		$l2 = new Link();
		$l2->operationRef = 'operationRef';
		$l2->operationId = 'operationId';
		$l2->parameters['foo'] = 'bar';
		$l2->requestBody = 'requestBody';
		$l2->description = 'description';
		$l2->server = $s2 = new Server('https://example.com');
		$l2->addExtension('x-a', null);
		self::assertSame(
			[
				'operationRef' => 'operationRef',
				'operationId' => 'operationId',
				'parameters' => [
					'foo' => 'bar',
				],
				'requestBody' => 'requestBody',
				'description' => 'description',
				'server' => $s2->toArray(),
				'x-a' => null,
			],
			$l2->toArray(),
		);
	}

}
