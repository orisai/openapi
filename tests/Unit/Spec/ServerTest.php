<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\Server;
use Orisai\OpenAPI\Spec\ServerVariable;
use PHPUnit\Framework\TestCase;

final class ServerTest extends TestCase
{

	public function test(): void
	{
		$s1 = new Server('https://example.com');
		self::assertSame('https://example.com', $s1->getUrl());
		self::assertSame([], $s1->getVariables());
		self::assertSame(
			[
				'url' => 'https://example.com',
			],
			$s1->toRaw(),
		);

		$s2 = new Server('https://example.com');
		self::assertSame('https://example.com', $s2->getUrl());
		$s2->description = 'description';

		$v1 = new ServerVariable('1');
		$s2->addVariable('v1', $v1);

		$v2 = new ServerVariable('2');
		$s2->addVariable('v2', $v2);

		self::assertSame(
			[
				'v1' => $v1,
				'v2' => $v2,
			],
			$s2->getVariables(),
		);

		$s2->addExtension('x-a', null);

		self::assertSame(
			[
				'url' => 'https://example.com',
				'description' => 'description',
				'variables' => [
					'v1' => $v1->toRaw(),
					'v2' => $v2->toRaw(),
				],
				'x-a' => null,
			],
			$s2->toRaw(),
		);
	}

}
