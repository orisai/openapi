<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

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
			$rb1->toArray(),
		);

		$rb2mt1 = new MediaType();
		$rb2mt1->setExample('json');

		$rb2mt2 = new MediaType();
		$rb2mt2->setExample('xml');

		$rb2 = new RequestBody();
		$rb2->description = 'description';
		$rb2->required = true;
		$rb2->addContent('application/json', $rb2mt1);
		$rb2->addContent('application/xml', $rb2mt2);
		$rb2->addExtension('x-a', null);

		self::assertSame(
			[
				'content' => [
					'application/json' => $rb2mt1->toArray(),
					'application/xml' => $rb2mt2->toArray(),
				],
				'description' => 'description',
				'required' => true,
				'x-a' => null,
			],
			$rb2->toArray(),
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

		$r->addContent('text/csv', $mt);
		$r->addContent('application/x-neon', $mt);
		$r->addContent('application/xml', $mt);
		$r->addContent('application/x-yaml', $mt);
		$r->addContent('application/json', $mt);

		self::assertSame(
			[
				'application/json',
				'application/xml',
				'application/x-neon',
				'application/x-yaml',
				'text/csv',
			],
			array_keys($r->getContent()),
		);
	}

	public function testContentTypeOrderToArray(): void
	{
		$r = new RequestBody();
		$mt = new MediaType();

		$r->addContent('text/csv', $mt);
		$r->addContent('application/x-neon', $mt);
		$r->addContent('application/xml', $mt);
		$r->addContent('application/x-yaml', $mt);
		$r->addContent('application/json', $mt);

		self::assertSame(
			[
				'content' => [
					'application/json' => [],
					'application/xml' => [],
					'application/x-neon' => [],
					'application/x-yaml' => [],
					'text/csv' => [],
				],
			],
			$r->toArray(),
		);
	}

	public function testInvalidContentType(): void
	{
		$rb = new RequestBody();

		$this->expectException(InvalidArgument::class);
		$this->expectExceptionMessage(<<<'MSG'
Context: Adding a media type 'invalid'.
Problem: Type is not a valid media type.
Hint: Validation is performed in compliance with
      https://www.rfc-editor.org/rfc/rfc2045#section-5.1 and
      https://www.rfc-editor.org/rfc/rfc7231#section-3.1.1.1
MSG);

		$rb->addContent('invalid', new MediaType());
	}

}
