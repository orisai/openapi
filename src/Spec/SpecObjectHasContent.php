<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Message;
use Orisai\OpenAPI\Utils\MediaTypeValidator;
use function assert;
use function strpos;
use function strtolower;
use function substr;
use function uksort;

/**
 * @internal
 */
trait SpecObjectHasContent
{

	/** @var array<non-empty-string, MediaType> */
	private array $content = [];

	/**
	 * @param non-empty-string $name
	 */
	public function addContent(string $name, MediaType $mediaType): void
	{
		$name = strtolower($name);
		if (!MediaTypeValidator::isValid($name)) {
			$message = Message::create()
				->withContext("Adding a media type '$name'.")
				->withProblem('Type is not a valid media type.')
				->with(
					'Hint',
					'Validation is performed in compliance with https://www.rfc-editor.org/rfc/rfc2045#section-5.1 ' .
					'and https://www.rfc-editor.org/rfc/rfc7231#section-3.1.1.1',
				);

			throw InvalidArgument::create()
				->withMessage($message);
		}

		$this->content[$name] = $mediaType;
	}

	/**
	 * @return array<non-empty-string, MediaType>
	 */
	public function getContent(): array
	{
		uksort($this->content, static function (string $a, string $b): int {
			$aPos = strpos($a, '/');
			assert($aPos !== false);
			$aLeft = substr($a, 0, $aPos);

			$bPos = strpos($b, '/');
			assert($bPos !== false);
			$bLeft = substr($b, 0, $bPos);

			$leftCompare = $aLeft <=> $bLeft;
			if ($leftCompare !== 0) {
				return $leftCompare;
			}

			$aHasExt = strpos($a, '/x-') !== false;
			$bHasExt = strpos($b, '/x-') !== false;

			if ($aHasExt && !$bHasExt) {
				return 1;
			}

			if (!$aHasExt && $bHasExt) {
				return -1;
			}

			return $a > $b ? 1 : -1;
		});

		return $this->content;
	}

}
