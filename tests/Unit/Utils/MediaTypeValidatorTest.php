<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Utils;

use Generator;
use Orisai\OpenAPI\Utils\MediaTypeValidator;
use PHPUnit\Framework\TestCase;
use function fclose;
use function fgetcsv;
use function fopen;
use function in_array;

final class MediaTypeValidatorTest extends TestCase
{

	/**
	 * @dataProvider provideValidTypes
	 */
	public function testValidTypes(string $mediaType): void
	{
		self::assertTrue(MediaTypeValidator::isValid($mediaType));
	}

	public function provideValidTypes(): Generator
	{
		foreach (require __DIR__ . '/../../Data/MediaTypes/custom.php' as $mediaType) {
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
		yield __DIR__ . '/../../Data/MediaTypes/application.csv';
		yield __DIR__ . '/../../Data/MediaTypes/audio.csv';
		yield __DIR__ . '/../../Data/MediaTypes/image.csv';
		yield __DIR__ . '/../../Data/MediaTypes/message.csv';
		yield __DIR__ . '/../../Data/MediaTypes/model.csv';
		yield __DIR__ . '/../../Data/MediaTypes/multipart.csv';
		yield __DIR__ . '/../../Data/MediaTypes/text.csv';
		yield __DIR__ . '/../../Data/MediaTypes/video.csv';
	}

	private function getMediaTypesFromCsv(string $file): Generator
	{
		if (($handle = fopen($file, 'r')) !== false) {
			while (($data = fgetcsv($handle)) !== false) {
				if (in_array($data[1], ['', 'Template'], true)) {
					continue;
				}

				yield $data[1];
			}

			fclose($handle);
		}
	}

	/**
	 * @dataProvider provideInvalidTypes
	 */
	public function testInvalidTypes(string $mediaType): void
	{
		self::assertFalse(MediaTypeValidator::isValid($mediaType));
	}

	public function provideInvalidTypes(): Generator
	{
		yield [
			'text',
			'text/ř',
			'text/x-ř',
		];
	}

	public function testCaseNonSensitivity(): void
	{
		self::assertTrue(MediaTypeValidator::isValid('APPLICATION/PDF'));
	}

}
