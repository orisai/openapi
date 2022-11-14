<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Generator;
use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\OpenAPI\Spec\Header;
use Orisai\OpenAPI\Spec\Link;
use Orisai\OpenAPI\Spec\MediaType;
use Orisai\OpenAPI\Spec\Reference;
use Orisai\OpenAPI\Spec\Response;
use PHPUnit\Framework\TestCase;
use function array_keys;

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
		$r2h1->setExample('h1');
		$r2->addHeader('transfer-encoding', $r2h1);

		$r2h2 = new Reference('ref');
		$r2->addHeader('trailer', $r2h2);

		self::assertSame(
			[
				'Transfer-Encoding' => $r2h1,
				'Trailer' => $r2h2,
			],
			$r2->getHeaders(),
		);

		$r2mt1 = new MediaType();
		$r2mt1->setExample('json');
		$r2->addContent('application/json', $r2mt1);

		$r2mt2 = new MediaType();
		$r2mt2->setExample('xml');
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
					'Transfer-Encoding' => $r2h1->toArray(),
					'Trailer' => $r2h2->toArray(),
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

	public function testInvalidHeaderName(): void
	{
		$r = new Response('desc');

		$this->expectException(InvalidArgument::class);
		$this->expectExceptionMessage(
			<<<'MSG'
Context: Adding Response Header with name 'á'.
Problem: Name is not valid HTTP header name.
Hint: Validation is performed in compliance with
      https://www.rfc-editor.org/rfc/rfc7230
MSG,
		);

		$r->addHeader('á', new Header());
	}

	/**
	 * @dataProvider provideInvalidLinkNameVariants
	 */
	public function testInvalidLinkNameVariants(string $key): void
	{
		$r = new Response('desc');
		$ref = new Reference('ref');

		$this->expectException(InvalidArgument::class);
		$this->expectExceptionMessage(
			<<<MSG
Context: Assigning a spec object 'Link' with key '$key'.
Problem: Key must match regular expression '^[a-zA-Z0-9\.\-_]+$'.
MSG,
		);

		$r->addLink($key, $ref);
	}

	public function provideInvalidLinkNameVariants(): Generator
	{
		yield ['až'];
		yield ['azAZ09.-ž'];
		yield ['žazAZ09.-'];
	}

	public function testLowercaseContentType(): void
	{
		$r = new Response('d');

		$mt1 = new MediaType();
		$mt2 = new MediaType();

		$r->addContent('application/pdf', $mt1);
		$r->addContent('APPLICATION/PDF', $mt2);

		self::assertSame(
			[
				'application/pdf' => $mt2,
			],
			$r->getContent(),
		);
	}

	public function testContentTypeOrder(): void
	{
		$r = new Response('d');
		$mt = new MediaType();

		$r->addContent('*/*', $mt);
		$r->addContent('text/csv', $mt);

		self::assertSame(
			[
				'text/csv',
				'*/*',
			],
			array_keys($r->getContent()),
		);
	}

	public function testContentTypeOrderToArray(): void
	{
		$r = new Response('d');
		$mt = new MediaType();

		$r->addContent('*/*', $mt);
		$r->addContent('text/csv', $mt);

		self::assertSame(
			[
				'description' => 'd',
				'content' => [
					'text/csv' => [],
					'*/*' => [],
				],
			],
			$r->toArray(),
		);
	}

	/**
	 * @dataProvider provideContentVariant
	 */
	public function testContentVariant(string $name): void
	{
		$response = new Response('desc');
		$mediaType = new MediaType();
		$response->addContent($name, $mediaType);

		self::assertSame([$name => $mediaType], $response->getContent());
	}

	public function provideContentVariant(): Generator
	{
		yield ['application/json'];
		yield ['application/*'];
		yield ['*/*'];
	}

	public function testInvalidContentType(): void
	{
		$r = new Response('d');

		$this->expectException(InvalidArgument::class);
		$this->expectExceptionMessage(
			<<<'MSG'
Context: Adding a media type 'invaliD'.
Problem: Type is not a valid media type or media type range.
Hint: Validation is performed in compliance with
      https://www.rfc-editor.org/rfc/rfc2045#section-5.1 and
      https://www.rfc-editor.org/rfc/rfc7231#section-3.1.1.1
MSG,
		);

		$r->addContent('invaliD', new MediaType());
	}

}
