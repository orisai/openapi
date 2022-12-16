<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Generator;
use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\OpenAPI\Enum\EncodingStyle;
use Orisai\OpenAPI\Spec\Encoding;
use Orisai\OpenAPI\Spec\Header;
use Orisai\OpenAPI\Spec\Reference;
use PHPUnit\Framework\TestCase;

final class EncodingTest extends TestCase
{

	public function test(): void
	{
		$e1 = new Encoding();
		self::assertSame([], $e1->getContentTypes());
		self::assertTrue($e1->getExplode());
		self::assertFalse($e1->getAllowReserved());
		self::assertSame([], $e1->toRaw());

		$e2 = new Encoding();

		$e2->addContentType('application/json');
		self::assertSame(
			['application/json'],
			$e2->getContentTypes(),
		);

		$e2h1 = new Header();
		$e2h1->setExample('h1');
		$e2->addHeader('transfer-encoding', $e2h1);

		$e2h2 = new Reference('ref');
		$e2->addHeader('trailer', $e2h2);

		self::assertSame(
			[
				'Transfer-Encoding' => $e2h1,
				'Trailer' => $e2h2,
			],
			$e2->getHeaders(),
		);

		$e2->setStyle(EncodingStyle::deepObject(), true);
		self::assertSame(EncodingStyle::deepObject(), $e2->getStyle());
		self::assertTrue($e2->getExplode());

		$e2->setAllowReserved();
		self::assertTrue($e2->getAllowReserved());

		$e2->addExtension('x-a', null);

		self::assertSame(
			[
				'contentType' => 'application/json',
				'headers' => [
					'Transfer-Encoding' => $e2h1->toRaw(),
					'Trailer' => $e2h2->toRaw(),
				],
				'style' => 'deepObject',
				'explode' => true,
				'allowReserved' => true,
				'x-a' => null,
			],
			$e2->toRaw(),
		);
	}

	public function testContentTypeOrder(): void
	{
		$encoding = new Encoding();

		$encoding->addContentType('*/*');
		$encoding->addContentType('text/csv');

		self::assertSame(
			[
				'text/csv',
				'*/*',
			],
			$encoding->getContentTypes(),
		);
	}

	public function testContentTypeOrderToArray(): void
	{
		$encoding = new Encoding();

		$encoding->addContentType('*/*');
		$encoding->addContentType('text/csv');

		self::assertSame(
			[
				'contentType' => 'text/csv, */*',
			],
			$encoding->toRaw(),
		);
	}

	/**
	 * @dataProvider provideContentVariant
	 */
	public function testContentVariant(string $name): void
	{
		$encoding = new Encoding();
		$encoding->addContentType($name);

		self::assertSame([$name], $encoding->getContentTypes());
	}

	public function provideContentVariant(): Generator
	{
		yield ['application/json'];
		yield ['application/*'];
		yield ['*/*'];
	}

	public function testInvalidContentType(): void
	{
		$encoding = new Encoding();

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

		$encoding->addContentType('invaliD');
	}

	public function testInvalidHeaderName(): void
	{
		$encoding = new Encoding();

		$this->expectException(InvalidArgument::class);
		$this->expectExceptionMessage(
			<<<'MSG'
Context: Adding Encoding Header with name 'á'.
Problem: Name is not valid HTTP header name.
Hint: Validation is performed in compliance with
      https://www.rfc-editor.org/rfc/rfc7230
MSG,
		);

		$encoding->addHeader('á', new Header());
	}

	public function testStyleAndExplode(): void
	{
		$encoding = new Encoding();
		self::assertSame(EncodingStyle::form(), $encoding->getStyle());
		self::assertTrue($encoding->getExplode());
		self::assertArrayNotHasKey('explode', $encoding->toRaw());

		$encoding->setStyle(EncodingStyle::pipeDelimited());
		self::assertSame(EncodingStyle::pipeDelimited(), $encoding->getStyle());
		self::assertFalse($encoding->getExplode());
		self::assertArrayNotHasKey('explode', $encoding->toRaw());

		$encoding->setStyle(EncodingStyle::pipeDelimited(), false);
		self::assertSame(EncodingStyle::pipeDelimited(), $encoding->getStyle());
		self::assertFalse($encoding->getExplode());
		self::assertArrayNotHasKey('explode', $encoding->toRaw());

		$encoding->setStyle(EncodingStyle::pipeDelimited(), true);
		self::assertSame(EncodingStyle::pipeDelimited(), $encoding->getStyle());
		self::assertTrue($encoding->getExplode());
		self::assertTrue($encoding->toRaw()['explode']);

		$encoding->setStyle(EncodingStyle::form());
		self::assertSame(EncodingStyle::form(), $encoding->getStyle());
		self::assertTrue($encoding->getExplode());
		self::assertArrayNotHasKey('explode', $encoding->toRaw());

		$encoding->setStyle(EncodingStyle::form(), true);
		self::assertSame(EncodingStyle::form(), $encoding->getStyle());
		self::assertTrue($encoding->getExplode());
		self::assertArrayNotHasKey('explode', $encoding->toRaw());

		$encoding->setStyle(EncodingStyle::form(), false);
		self::assertSame(EncodingStyle::form(), $encoding->getStyle());
		self::assertFalse($encoding->getExplode());
		self::assertFalse($encoding->toRaw()['explode']);
	}

}
