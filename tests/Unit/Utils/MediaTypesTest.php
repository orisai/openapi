<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Utils;

use Generator;
use Orisai\OpenAPI\Utils\MediaTypes;
use PHPUnit\Framework\TestCase;
use function fclose;
use function fgetcsv;
use function fopen;
use function in_array;

final class MediaTypesTest extends TestCase
{

	/**
	 * @dataProvider provideFormat
	 */
	public function testFormat(string $formatted, string $mediaType): void
	{
		self::assertSame($formatted, MediaTypes::format($mediaType));
	}

	public function provideFormat(): Generator
	{
		yield ['application/json', 'application/json'];
		yield ['application/json', 'Application/Json'];
		yield ['application/json', 'APPLICATION/JSON'];
	}

	/**
	 * @dataProvider provideValidTypes
	 */
	public function testValidTypes(string $mediaType): void
	{
		self::assertTrue(MediaTypes::isValid($mediaType));
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
		self::assertFalse(MediaTypes::isValid($mediaType));
	}

	public function provideInvalidTypes(): Generator
	{
		yield [
			'text',
			'text/ř',
			'text/x-ř',
			'*/json',
			'*/*',
			'application/json,application/xml',
			'application/json,application/*',
		];
	}

	public function testCaseNonSensitivity(): void
	{
		self::assertTrue(MediaTypes::isValid('APPLICATION/PDF'));
	}

	public function testSort(): void
	{
		$array = [
			'text/*' => null,
			'*/*' => null,
			'text/csv' => null,
			'application/x-neon' => null,
			'application/xml' => null,
			'application/x-yaml' => null,
			'application/json' => null,
		];

		MediaTypes::sortTypesInKeys($array);

		self::assertSame(
			[
				'application/json' => null,
				'application/xml' => null,
				'application/x-neon' => null,
				'application/x-yaml' => null,
				'text/csv' => null,
				'text/*' => null,
				'*/*' => null,
			],
			$array,
		);
	}

}
