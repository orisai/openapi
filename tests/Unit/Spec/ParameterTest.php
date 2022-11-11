<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Generator;
use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Logic\InvalidState;
use Orisai\Exceptions\Message;
use Orisai\OpenAPI\Enum\ParameterIn;
use Orisai\OpenAPI\Enum\ParameterStyle;
use Orisai\OpenAPI\Spec\Example;
use Orisai\OpenAPI\Spec\MediaType;
use Orisai\OpenAPI\Spec\Parameter;
use Orisai\OpenAPI\Spec\Reference;
use PHPUnit\Framework\TestCase;
use stdClass;
use function array_filter;
use function fopen;
use function in_array;

final class ParameterTest extends TestCase
{

	public function testDefault(): void
	{
		$p1 = new Parameter('p1', ParameterIn::query());
		self::assertSame('p1', $p1->getName());
		self::assertSame(ParameterIn::query(), $p1->getIn());
		self::assertSame(
			[
				'name' => 'p1',
				'in' => 'query',
			],
			$p1->toArray(),
		);

		$p2 = new Parameter('p2', ParameterIn::path());
		self::assertSame('p2', $p2->getName());
		self::assertSame(ParameterIn::path(), $p2->getIn());
		self::assertSame(
			[
				'name' => 'p2',
				'in' => 'path',
				'required' => true,
			],
			$p2->toArray(),
		);

		$p3 = new Parameter('p3', ParameterIn::cookie());
		self::assertSame('p3', $p3->getName());
		self::assertSame(ParameterIn::cookie(), $p3->getIn());
		self::assertSame(
			[
				'name' => 'p3',
				'in' => 'cookie',
			],
			$p3->toArray(),
		);

		$p4 = new Parameter('transfer-encoding', ParameterIn::header());
		self::assertSame('Transfer-Encoding', $p4->getName());
		self::assertSame(ParameterIn::header(), $p4->getIn());
		self::assertSame(
			[
				'name' => 'Transfer-Encoding',
				'in' => 'header',
			],
			$p4->toArray(),
		);
	}

	public function testFull(): void
	{
		$p = new Parameter('p', ParameterIn::cookie());
		$p->description = 'description';
		$p->setRequired(true);
		$p->deprecated = true;
		$p->setStyle(ParameterStyle::form(), false);
		$p->schema->setExample(null);
		$p->setExample(null);

		$p->addExample('foo', $pe1 = new Example());
		$pe1->description = 'desc';
		$p->addExample('bar', $pe2 = new Reference('ref'));
		self::assertSame(
			[
				'foo' => $pe1,
				'bar' => $pe2,
			],
			$p->getExamples(),
		);

		$p->addContent('application/json', $pc1 = new MediaType());
		$pc1->setExample(null);

		$p->addExtension('x-a', null);

		self::assertSame(
			[
				'name' => 'p',
				'in' => 'cookie',
				'description' => 'description',
				'required' => true,
				'deprecated' => true,
				'explode' => false,
				'schema' => $p->schema->toArray(),
				'example' => null,
				'examples' => [
					'foo' => $pe1->toArray(),
					'bar' => $pe2->toArray(),
				],
				'content' => [
					'application/json' => $pc1->toArray(),
				],
				'x-a' => null,
			],
			$p->toArray(),
		);
	}

	/**
	 * @dataProvider provideInvalidPathParameterName
	 */
	public function testInvalidPathParameterName(string $name): void
	{
		$this->expectException(InvalidArgument::class);
		$this->expectExceptionMessage(
			<<<MSG
Context: Creating Parameter with name '$name'.
Problem: Characters '{}/' are not allowed in Parameter in=path.
MSG,
		);

		new Parameter($name, ParameterIn::path());
	}

	public function provideInvalidPathParameterName(): Generator
	{
		yield ['a{'];
		yield ['}a'];
		yield ['a/a'];
	}

	public function testInvalidHeaderName(): void
	{
		$this->expectException(InvalidArgument::class);
		$this->expectExceptionMessage(
			<<<'MSG'
Context: Creating a Parameter with name 'á' in 'header'.
Problem: Name is not valid HTTP header name.
Hint: Validation is performed in compliance with
      https://www.rfc-editor.org/rfc/rfc7230
MSG,
		);

		new Parameter('á', ParameterIn::header());
	}

	/**
	 * @dataProvider provideRequired
	 */
	public function testRequired(Parameter $parameter): void
	{
		self::assertNotContains('required', $parameter->toArray());

		$parameter->setRequired();
		self::assertTrue($parameter->toArray()['required']);

		$parameter->setRequired(false);
		self::assertNotContains('required', $parameter->toArray());
	}

	public function provideRequired(): Generator
	{
		yield [new Parameter('name', ParameterIn::cookie())];
		yield [new Parameter('name', ParameterIn::header())];
		yield [new Parameter('name', ParameterIn::query())];
	}

	public function testInPathNotRequired(): void
	{
		$p = new Parameter('name', ParameterIn::path());
		self::assertTrue($p->toArray()['required']);

		$p->setRequired();
		self::assertTrue($p->toArray()['required']);

		$this->expectException(InvalidArgument::class);
		$this->expectExceptionMessage(
			<<<'MSG'
Context: Setting Parameter required to false.
Problem: Parameter is in path and as such must be required.
MSG,
		);

		$p->setRequired(false);
	}

	/**
	 * @param list<ParameterStyle> $styles
	 *
	 * @dataProvider provideStyle
	 */
	public function testStyle(Parameter $parameter, array $styles): void
	{
		foreach ($styles as $style) {
			$parameter->setStyle($style);
			self::assertSame($style, $parameter->getStyle());
			self::assertSame($style->getDefaultExplode(), $parameter->getExplode());

			if ($style === $parameter->getIn()->getDefaultStyle()) {
				self::assertNotContains('style', $parameter->toArray());
			} else {
				self::assertArrayHasKey('style', $parameter->toArray());
				self::assertSame($style->value, $parameter->toArray()['style']);
			}
		}
	}

	public function provideStyle(): Generator
	{
		yield [
			new Parameter('name', ParameterIn::cookie()),
			[ParameterStyle::form()],
		];

		yield [
			new Parameter('name', ParameterIn::header()),
			[ParameterStyle::simple()],
		];

		yield [
			new Parameter('name', ParameterIn::path()),
			[ParameterStyle::simple(), ParameterStyle::label(), ParameterStyle::matrix()],
		];

		yield [
			new Parameter('name', ParameterIn::query()),
			[ParameterStyle::form(), ParameterStyle::spaceDelimited(), ParameterStyle::pipeDelimited(), ParameterStyle::deepObject()],
		];
	}

	/**
	 * @runInSeparateProcess
	 * @dataProvider provideNotAllowedStyle
	 */
	public function testNotAllowedStyle(Parameter $parameter, string $allowed): void
	{
		Message::$lineLength = 150;
		$styles = array_filter(
			ParameterStyle::cases(),
			static fn (ParameterStyle $style): bool => !in_array($style, $parameter->getIn()->getAllowedStyles(), true),
		);

		self::assertNotEmpty($styles);
		foreach ($styles as $style) {
			$e = null;
			try {
				$parameter->setStyle($style);
			} catch (InvalidArgument $e) {
				// Handled bellow
			}

			self::assertNotNull($e);
			self::assertSame(
				<<<MSG
Context: Setting Parameter style to '$style->value'.
Problem: Allowed styles for parameter in '{$parameter->getIn()->value}' are $allowed.
MSG,
				$e->getMessage(),
			);
		}
	}

	public function provideNotAllowedStyle(): Generator
	{
		yield [new Parameter('name', ParameterIn::cookie()), "'form'"];
		yield [new Parameter('name', ParameterIn::header()), "'simple'"];
		yield [new Parameter('name', ParameterIn::path()), "'simple', 'label', 'matrix'"];
		yield [new Parameter('name', ParameterIn::query()), "'form', 'spaceDelimited', 'pipeDelimited', 'deepObject'"];
	}

	public function testExplode(): void
	{
		$parameter = new Parameter('name', ParameterIn::query());
		self::assertTrue($parameter->getExplode());

		$parameter->setStyle(ParameterStyle::pipeDelimited());
		self::assertFalse($parameter->getExplode());

		$parameter->setStyle(ParameterStyle::pipeDelimited(), false);
		self::assertFalse($parameter->getExplode());

		$parameter->setStyle(ParameterStyle::pipeDelimited(), true);
		self::assertTrue($parameter->getExplode());

		$parameter->setStyle(ParameterStyle::form());
		self::assertTrue($parameter->getExplode());

		$parameter->setStyle(ParameterStyle::form(), true);
		self::assertTrue($parameter->getExplode());

		$parameter->setStyle(ParameterStyle::form(), false);
		self::assertFalse($parameter->getExplode());
	}

	public function testInQueryReserved(): void
	{
		$parameter = new Parameter('name', ParameterIn::query());

		$parameter->setAllowReserved();
		self::assertSame(
			[
				'name' => 'name',
				'in' => 'query',
				'allowReserved' => true,
			],
			$parameter->toArray(),
		);

		$parameter->setAllowReserved(false);
		self::assertSame(
			[
				'name' => 'name',
				'in' => 'query',
			],
			$parameter->toArray(),
		);
	}

	/**
	 * @dataProvider provideNotInQuery
	 */
	public function testNotInQueryReserved(Parameter $parameter): void
	{
		$this->expectException(InvalidArgument::class);
		$this->expectExceptionMessage(
			<<<'MSG'
Context: Setting Parameter allowReserved.
Problem: Parameter is not in query and only query parameters can have
         allowReserved.
MSG,
		);

		$parameter->setAllowReserved();
	}

	public function testInQueryEmptyValue(): void
	{
		$parameter = new Parameter('name', ParameterIn::query());

		$parameter->setAllowEmptyValue();
		self::assertSame(
			[
				'name' => 'name',
				'in' => 'query',
				'allowEmptyValue' => true,
			],
			$parameter->toArray(),
		);

		$parameter->setAllowEmptyValue(false);
		self::assertSame(
			[
				'name' => 'name',
				'in' => 'query',
			],
			$parameter->toArray(),
		);
	}

	/**
	 * @dataProvider provideNotInQuery
	 */
	public function testNotInQueryEmptyValue(Parameter $parameter): void
	{
		$this->expectException(InvalidArgument::class);
		$this->expectExceptionMessage(
			<<<'MSG'
Context: Setting Parameter allowEmptyValue.
Problem: Parameter is not in query and only query parameters can have
         allowEmptyValue.
MSG,
		);

		$parameter->setAllowEmptyValue();
	}

	public function provideNotInQuery(): Generator
	{
		yield [new Parameter('name', ParameterIn::cookie())];
		yield [new Parameter('name', ParameterIn::header())];
		yield [new Parameter('name', ParameterIn::path())];
	}

	public function testSetValue(): void
	{
		$parameter = new Parameter('name', ParameterIn::path());

		$parameter->setExample(null);
		self::assertNull($parameter->getExample());

		$parameter->setExample('string');
		self::assertSame('string', $parameter->getExample());

		$parameter->setExample($o = new stdClass());
		self::assertSame($o, $parameter->getExample());

		$parameter->setExample([$o]);
		self::assertSame([$o], $parameter->getExample());
	}

	/**
	 * @param mixed $value
	 *
	 * @dataProvider provideUnsupportedValue
	 * @runInSeparateProcess
	 */
	public function testUnsupportedValue($value, string $unsupportedType): void
	{
		// Workaround - yielded resource is for some reason cast to 0
		if ($value === 'resource') {
			$value = fopen(__FILE__, 'r');
		}

		$parameter = new Parameter('name', ParameterIn::path());

		$this->expectException(InvalidArgument::class);
		$this->expectExceptionMessage(
			<<<MSG
Context: Setting an example.
Problem: Value contains type '$unsupportedType', which is not allowed.
Solution: Change type to one of supported - scalar, null, array or stdClass.
MSG,
		);

		Message::$lineLength = 150;
		$parameter->setExample($value);
	}

	public function provideUnsupportedValue(): Generator
	{
		yield [InvalidArgument::create(), InvalidArgument::class];

		yield [
			[
				'a' => 'b',
				'foo' => [
					'bar' => [
						InvalidArgument::create(),
					],
				],
			],
			InvalidArgument::class,
		];

		yield [
			'resource',
			'resource (stream)',
		];
	}

	public function testGetNoValue(): void
	{
		$parameter = new Parameter('name', ParameterIn::path());

		self::assertFalse($parameter->hasExample());

		$this->expectException(InvalidState::class);
		$this->expectExceptionMessage(
			<<<'MSG'
Context: Getting the example value.
Problem: Example value is not set and so cannot be get.
Solution: Check with hasExample().
MSG,
		);

		$parameter->getExample();
	}

	public function testMultipleContents(): void
	{
		$parameter = new Parameter('name', ParameterIn::path());
		self::assertSame([], $parameter->getContent());

		$mt1 = new MediaType();
		$mt1->setExample(null);
		$parameter->addContent('application/json', $mt1);

		// Same media type is okay
		$mt2 = new MediaType();
		$parameter->addContent('application/json', $mt2);

		$this->expectException(InvalidState::class);
		$this->expectExceptionMessage(
			<<<'MSG'
Context: Adding content with media type 'application/xml' to a Parameter.
Problem: Parameter content can contain only one entry, given one is second.
MSG,
		);

		$parameter->addContent('application/xml', new MediaType());
	}

}
