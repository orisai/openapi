<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\ExternalDocumentation;
use Orisai\OpenAPI\Spec\Info;
use Orisai\OpenAPI\Spec\OpenAPI;
use Orisai\OpenAPI\Spec\PathItem;
use Orisai\OpenAPI\Spec\Reference;
use Orisai\OpenAPI\Spec\RequestBody;
use Orisai\OpenAPI\Spec\SecurityRequirement;
use Orisai\OpenAPI\Spec\Server;
use Orisai\OpenAPI\Spec\Tag;
use PHPUnit\Framework\TestCase;

final class OpenAPITest extends TestCase
{

	public function test(): void
	{
		$i1 = new Info('api', 'version');
		$oa1 = new OpenAPI($i1);
		self::assertSame(
			[
				'openapi' => '3.1.0',
				'info' => $i1->toArray(),
				'servers' => [
					(new Server('/'))->toArray(),
				],
			],
			$oa1->toArray(),
		);

		$i2 = new Info('api', 'version');
		$oa2 = new OpenAPI($i2);
		$oa2->jsonSchemaDialect = 'dialect';

		$oa2->addServer($oa2s1 = new Server('https://example.com'));
		$oa2->addServer($oa2s1);
		$oa2->addServer($oa2s2 = new Server('https://example2.com'));
		self::assertSame(
			[$oa2s1, $oa2s2],
			$oa2->getServers(),
		);

		$oa2->paths->addPath('/foo', new PathItem());

		$oa2->addWebhook('foo', $oa2wh1 = new PathItem());
		$oa2->addWebhook('bar', $oa2wh2 = new Reference('bar'));
		self::assertSame(
			[
				'foo' => $oa2wh1,
				'bar' => $oa2wh2,
			],
			$oa2->getWebhooks(),
		);

		$oa2->components->addRequestBody('foo', new RequestBody([]));

		$oa2->addSecurityRequirement($oa2sr1 = new SecurityRequirement());
		$oa2->addSecurityRequirement($oa2sr1);
		$oa2->addSecurityRequirement($oa2sr2 = new SecurityRequirement());
		$oa2sr2->requirements['api_key'] = [];
		self::assertSame(
			[$oa2sr1, $oa2sr2],
			$oa2->getSecurityRequirements(),
		);

		$oa2->addTag($oa2t1 = new Tag('t1'));
		$oa2->addTag($oa2t1);
		$oa2->addTag($oa2t2 = new Tag('t2'));
		self::assertSame(
			[$oa2t1, $oa2t2],
			$oa2->getTags(),
		);

		$oa2->externalDocs = $oa2ed = new ExternalDocumentation('https://example.com');
		$oa2->addExtension('x-a', null);

		self::assertSame(
			[
				'openapi' => '3.1.0',
				'info' => $i2->toArray(),
				'jsonSchemaDialect' => 'dialect',
				'servers' => [
					$oa2s1->toArray(),
					$oa2s2->toArray(),
				],
				'paths' => $oa2->paths->toArray(),
				'webhooks' => [
					'foo' => $oa2wh1->toArray(),
					'bar' => $oa2wh2->toArray(),
				],
				'components' => $oa2->components->toArray(),
				'security' => [
					$oa2sr1->toArray(),
					$oa2sr2->toArray(),
				],
				'tags' => [
					$oa2t1->toArray(),
					$oa2t2->toArray(),
				],
				'externalDocs' => $oa2ed->toArray(),
				'x-a' => null,
			],
			$oa2->toArray(),
		);
	}

}
