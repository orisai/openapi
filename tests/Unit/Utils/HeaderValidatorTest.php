<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Utils;

use Generator;
use Orisai\OpenAPI\Utils\HeaderValidator;
use PHPUnit\Framework\TestCase;
use function fclose;
use function fgetcsv;
use function fopen;

final class HeaderValidatorTest extends TestCase
{

	/**
	 * @dataProvider provideNameFormat
	 */
	public function testNameFormat(string $formatted, string $name): void
	{
		self::assertSame($formatted, HeaderValidator::formatName($name));
	}

	public function provideNameFormat(): Generator
	{
		yield ['Transfer-Encoding', 'transfer-encoding'];
		yield ['Transfer-Encoding', 'tRaNsFeR-EnCoDiNg'];
		yield ['Transfer-Encoding', 'Transfer-Encoding'];
		yield ['Transfer-Encoding', 'TRANSFER-ENCODING'];
	}

	/**
	 * @dataProvider provideValidNames
	 */
	public function testValidNames(string $name): void
	{
		self::assertTrue(HeaderValidator::isNameValid($name));
	}

	public function provideValidNames(): Generator
	{
		foreach (require __DIR__ . '/../../Data/Headers/custom.php' as $mediaType) {
			yield [$mediaType];
		}

		foreach ($this->getCsvFiles() as $file) {
			foreach ($this->getMediaTypesFromCsv($file) as $mediaType) {
				yield [$mediaType];
			}
		}
	}

	private function getCsvFiles(): Generator
	{
		yield __DIR__ . '/../../Data/Headers/perm-headers.csv';
		yield __DIR__ . '/../../Data/Headers/prov-headers.csv';
	}

	private function getMediaTypesFromCsv(string $file): Generator
	{
		if (($handle = fopen($file, 'r')) !== false) {
			while (($data = fgetcsv($handle)) !== false) {
				if ($data[0] === 'Header Field Name') {
					continue;
				}

				yield $data[0];
			}

			fclose($handle);
		}
	}

	/**
	 * @dataProvider provideInvalidNames
	 */
	public function testInvalidNames(string $name): void
	{
		self::assertFalse(HeaderValidator::isNameValid($name));
	}

	public function provideInvalidNames(): Generator
	{
		yield [
			'a/',
			'á',
		];
	}

	public function testCaseNonSensitivity(): void
	{
		self::assertTrue(HeaderValidator::isNameValid('TRANSFER-ENCODING'));
	}

}
