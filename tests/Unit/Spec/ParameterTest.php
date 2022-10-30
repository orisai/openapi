<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Generator;
use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Message;
use Orisai\OpenAPI\Enum\ParameterIn;
use Orisai\OpenAPI\Enum\ParameterStyle;
use Orisai\OpenAPI\Spec\Example;
use Orisai\OpenAPI\Spec\MediaType;
use Orisai\OpenAPI\Spec\Parameter;
use Orisai\OpenAPI\Spec\Reference;
use PHPUnit\Framework\TestCase;
use function array_filter;
use function in_array;

final class ParameterTest extends TestCase
{

	public function testDefault(): void
	{
		$p1 = new Parameter('p1', ParameterIn::query());
		self::assertSame(
			[
				'name' => 'p1',
				'in' => 'query',
				'explode' => true,
			],
			$p1->toArray(),
		);

		$p2 = new Parameter('p2', ParameterIn::path());
		self::assertSame(
			[
				'name' => 'p2',
				'in' => 'path',
				'required' => true,
			],
			$p2->toArray(),
		);

		$p3 = new Parameter('p3', ParameterIn::cookie());
		self::assertSame(
			[
				'name' => 'p3',
				'in' => 'cookie',
				'explode' => true,
			],
			$p3->toArray(),
		);

		$p4 = new Parameter('p4', ParameterIn::header());
		self::assertSame(
			[
				'name' => 'p4',
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
		$p->setStyle(ParameterStyle::form());
		$p->explode = true;
		$p->schema->example = null;
		$p->example = null;

		$p->examples['foo'] = $pe1 = new Example();
		$pe1->description = 'desc';
		$p->examples['bar'] = $pe2 = new Reference('ref');

		$p->content['application/json'] = $pc1 = new MediaType();
		$pc1->example = 'example';
		$p->content['application/xml'] = $pc2 = new MediaType();

		$p->addExtension('x-a', null);

		self::assertSame(
			[
				'name' => 'p',
				'in' => 'cookie',
				'description' => 'description',
				'required' => true,
				'deprecated' => true,
				'explode' => true,
				'schema' => $p->schema->toArray(),
				'example' => null,
				'examples' => [
					'foo' => $pe1->toArray(),
					'bar' => $pe2->toArray(),
				],
				'content' => [
					'application/json' => $pc1->toArray(),
					'application/xml' => $pc2->toArray(),
				],
				'x-a' => null,
			],
			$p->toArray(),
		);
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
		$this->expectExceptionMessage(<<<'MSG'
Context: Setting Parameter required to false.
Problem: Parameter is in path and as such must be required.
MSG);

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

			if ($style === $parameter->in->getDefaultStyle()) {
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
			static fn (ParameterStyle $style): bool => !in_array($style, $parameter->in->getAllowedStyles(), true),
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
Problem: Allowed styles for parameter in '{$parameter->in->value}' are $allowed.
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

	public function testInQueryReserved(): void
	{
		$parameter = new Parameter('name', ParameterIn::query());

		$parameter->setAllowReserved();
		self::assertSame(
			[
				'name' => 'name',
				'in' => 'query',
				'explode' => true,
				'allowReserved' => true,
			],
			$parameter->toArray(),
		);

		$parameter->setAllowReserved(false);
		self::assertSame(
			[
				'name' => 'name',
				'in' => 'query',
				'explode' => true,
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
		$this->expectExceptionMessage(<<<'MSG'
Context: Setting Parameter allowReserved.
Problem: Parameter is not in query and only query parameters can have
         allowReserved.
MSG);

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
				'explode' => true,
			],
			$parameter->toArray(),
		);

		$parameter->setAllowEmptyValue(false);
		self::assertSame(
			[
				'name' => 'name',
				'in' => 'query',
				'explode' => true,
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
		$this->expectExceptionMessage(<<<'MSG'
Context: Setting Parameter allowEmptyValue.
Problem: Parameter is not in query and only query parameters can have
         allowEmptyValue.
MSG);

		$parameter->setAllowEmptyValue();
	}

	public function provideNotInQuery(): Generator
	{
		yield [new Parameter('name', ParameterIn::cookie())];
		yield [new Parameter('name', ParameterIn::header())];
		yield [new Parameter('name', ParameterIn::path())];
	}

}
