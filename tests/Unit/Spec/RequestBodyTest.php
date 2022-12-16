<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Generator;
use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\OpenAPI\Spec\MediaType;
use Orisai\OpenAPI\Spec\RequestBody;
use PHPUnit\Framework\TestCase;
use function array_keys;

final class RequestBodyTest extends TestCase
{

	public function test(): void
	{
		$rb1 = new RequestBody();

		self::assertSame(
			[
				'content' => [],
			],
			$rb1->toRaw(),
		);

		$rb2mt1 = new MediaType();
		$rb2mt1->setExample('json');

		$rb2mt2 = new MediaType();
		$rb2mt2->setExample('any');

		$rb2 = new RequestBody();
		$rb2->description = 'description';
		$rb2->required = true;

		$rb2->addContent('application/json', $rb2mt1);
		$rb2->addContent('application/xml', $rb2mt2);

		self::assertSame(
			[
				'application/json' => $rb2mt1,
				'application/xml' => $rb2mt2,
			],
			$rb2->getContent(),
		);

		$rb2->addExtension('x-a', null);

		self::assertSame(
			[
				'content' => [
					'application/json' => $rb2mt1->toRaw(),
					'application/xml' => $rb2mt2->toRaw(),
				],
				'description' => 'description',
				'required' => true,
				'x-a' => null,
			],
			$rb2->toRaw(),
		);
	}

	public function testLowercaseContentType(): void
	{
		$r = new RequestBody();

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
		$r = new RequestBody();
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
		$r = new RequestBody();
		$mt = new MediaType();

		$r->addContent('*/*', $mt);
		$r->addContent('text/csv', $mt);

		self::assertSame(
			[
				'content' => [
					'text/csv' => [],
					'*/*' => [],
				],
			],
			$r->toRaw(),
		);
	}

	/**
	 * @dataProvider provideContentVariant
	 */
	public function testContentVariant(string $name): void
	{
		$requestBody = new RequestBody();
		$mediaType = new MediaType();
		$requestBody->addContent($name, $mediaType);

		self::assertSame([$name => $mediaType], $requestBody->getContent());
	}

	public function provideContentVariant(): Generator
	{
		yield ['application/json'];
		yield ['application/*'];
		yield ['*/*'];
	}

	public function testInvalidContentType(): void
	{
		$rb = new RequestBody();

		$this->expectException(InvalidArgument::class);
		$this->expectExceptionMessage(<<<'MSG'
Context: Adding a media type 'invaliD'.
Problem: Type is not a valid media type or media type range.
Hint: Validation is performed in compliance with
      https://www.rfc-editor.org/rfc/rfc2045#section-5.1 and
      https://www.rfc-editor.org/rfc/rfc7231#section-3.1.1.1
MSG);

		$rb->addContent('invaliD', new MediaType());
	}

}
