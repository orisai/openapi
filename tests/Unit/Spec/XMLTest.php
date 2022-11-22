<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\OpenAPI\Spec\XML;
use PHPUnit\Framework\TestCase;

final class XMLTest extends TestCase
{

	public function test(): void
	{
		$x1 = new XML();
		self::assertSame([], $x1->toArray());

		$x2 = new XML();
		$x2->setName(null);
		$x2->setName('name');
		$x2->namespace = 'namespace';
		$x2->setPrefix(null);
		$x2->setPrefix('prefix');
		$x2->attribute = true;
		$x2->wrapped = true;
		$x2->addExtension('x-a', null);
		self::assertSame(
			[
				'name' => 'name',
				'namespace' => 'namespace',
				'prefix' => 'prefix',
				'attribute' => true,
				'wrapped' => true,
				'x-a' => null,
			],
			$x2->toArray(),
		);
	}

	public function testInvalidName(): void
	{
		$xml = new XML();

		$this->expectException(InvalidArgument::class);
		$this->expectExceptionMessage(
			<<<'MSG'
Context: Setting XML name with value '@'.
Problem: Value is not valid in context of xml tag name.
MSG,
		);

		$xml->setName('@');
	}

	public function testInvalidPrefix(): void
	{
		$xml = new XML();

		$this->expectException(InvalidArgument::class);
		$this->expectExceptionMessage(
			<<<'MSG'
Context: Setting XML prefix with value '@'.
Problem: Value is not valid in context of xml tag name.
MSG,
		);

		$xml->setPrefix('@');
	}

}
