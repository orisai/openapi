<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Message;
use Orisai\OpenAPI\Utils\SpecUtils;
use function str_starts_with;

final class Paths implements SpecObject
{

	use SupportsSpecExtensions;

	/** @var array<string, PathItem> */
	private array $paths = [];

	public function addPath(string $path, PathItem $item): void
	{
		if (!str_starts_with($path, '/')) {
			$message = Message::create()
				->withContext("Adding path '$path'.")
				->withProblem("Path musts start with '/'.");

			throw InvalidArgument::create()
				->withMessage($message);
		}

		$this->paths[$path] = $item;
	}

	public function toArray(): array
	{
		$data = SpecUtils::specsToArray($this->paths);
		$this->addExtensionsToData($data);

		return $data;
	}

}
