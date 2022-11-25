<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Validation;

use Orisai\OpenAPI\Validation\ValidationResult;
use PHPUnit\Framework\TestCase;

final class ValidationResultTest extends TestCase
{

	public function test(): void
	{
		$errors = ['foo', 'bar'];
		$warnings = ['lorem', 'ipsum'];

		$result = new ValidationResult([], []);
		self::assertTrue($result->isOk());
		self::assertSame([], $result->getErrors());
		self::assertSame([], $result->getWarnings());

		$result = new ValidationResult($errors, []);
		self::assertFalse($result->isOk());
		self::assertSame($errors, $result->getErrors());
		self::assertSame([], $result->getWarnings());

		$result = new ValidationResult([], $warnings);
		self::assertFalse($result->isOk());
		self::assertSame([], $result->getErrors());
		self::assertSame($warnings, $result->getWarnings());

		$result = new ValidationResult($errors, $warnings);
		self::assertFalse($result->isOk());
		self::assertSame($errors, $result->getErrors());
		self::assertSame($warnings, $result->getWarnings());
	}

}
