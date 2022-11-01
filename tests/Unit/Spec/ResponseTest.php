<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Spec\Header;
use Orisai\OpenAPI\Spec\Link;
use Orisai\OpenAPI\Spec\MediaType;
use Orisai\OpenAPI\Spec\Response;
use PHPUnit\Framework\TestCase;

final class ResponseTest extends TestCase
{

	public function test(): void
	{
		$r1 = new Response('description');
		self::assertSame(
			[
				'description' => 'description',
			],
			$r1->toArray(),
		);

		$r2 = new Response('description');

		$r2h1 = new Header();
		$r2h1->example = 'h1';
		$r2->addHeader('h1', $r2h1);

		$r2h2 = new Header();
		$r2h2->example = 'h2';
		$r2->addHeader('h2', $r2h2);

		self::assertSame(
			[
				'h1' => $r2h1,
				'h2' => $r2h2,
			],
			$r2->getHeaders(),
		);

		$r2mt1 = new MediaType();
		$r2mt1->example = 'json';
		$r2->addContent('application/json', $r2mt1);

		$r2mt2 = new MediaType();
		$r2mt2->example = 'xml';
		$r2->addContent('application/xml', $r2mt2);

		self::assertSame(
			[
				'application/json' => $r2mt1,
				'application/xml' => $r2mt2,
			],
			$r2->getContent(),
		);

		$r2l1 = new Link();
		$r2->addLink('l1', $r2l1);

		$r2l2 = new Link();
		$r2l2->description = 'l2';
		$r2->addLink('l2', $r2l2);

		self::assertSame(
			[
				'l1' => $r2l1,
				'l2' => $r2l2,
			],
			$r2->getLinks(),
		);

		$r2->addExtension('x-a', null);

		self::assertSame(
			[
				'description' => 'description',
				'headers' => [
					'h1' => $r2h1->toArray(),
					'h2' => $r2h2->toArray(),
				],
				'content' => [
					'application/json' => $r2mt1->toArray(),
					'application/xml' => $r2mt2->toArray(),
				],
				'links' => [
					'l1' => $r2l1->toArray(),
					'l2' => $r2l2->toArray(),
				],
				'x-a' => null,
			],
			$r2->toArray(),
		);
	}

}
