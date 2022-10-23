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
		self::assertSame(
			[
				'url' => 'https://example.com',
			],
			$s1->toArray(),
		);

		$s2 = new Server('https://example.com');
		$s2->description = 'description';
		$v1 = new ServerVariable('1');
		$v2 = new ServerVariable('2');
		$s2->variables['v1'] = $v1;
		$s2->variables['v2'] = $v2;
		self::assertSame(
			[
				'url' => 'https://example.com',
				'description' => 'description',
				'variables' => [
					'v1' => $v1->toArray(),
					'v2' => $v2->toArray(),
				],
			],
			$s2->toArray(),
		);
	}

}
