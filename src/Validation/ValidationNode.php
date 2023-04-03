<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Validation;

use function array_merge;
use function implode;

/**
 * @internal
 */
final class ValidationNode
{

	/** @var array<int|string, self> */
	private array $subNodes = [];

	/** @var list<string> */
	private array $errors = [];

	/** @var list<string> */
	private array $warnings = [];

	/**
	 * @param string|int $path
	 */
	public function getSubNode($path): self
	{
		return $this->subNodes[$path]
			?? $this->subNodes[$path] = new self();
	}

	public function addError(string $error): void
	{
		$this->errors[] = $error;
	}

	public function addWarning(string $warning): void
	{
		$this->warnings[] = $warning;
	}

	/**
	 * @return list<string>
	 */
	public function getAllErrors(): array
	{
		return $this->getNodeErrors($this, []);
	}

	/**
	 * @param list<int|string> $path
	 * @return list<string>
	 */
	private function getNodeErrors(self $node, array $path): array
	{
		$errorsByNode = [];
		$errorsByNode[] = $this->getPrefixedMessages($node->errors, $path);

		foreach ($node->subNodes as $subPath => $subNode) {
			$errorsByNode[] = $this->getNodeErrors($subNode, array_merge($path, [$subPath]));
		}

		return array_merge(...$errorsByNode);
	}

	/**
	 * @return list<string>
	 */
	public function getAllWarnings(): array
	{
		return $this->getNodeWarnings($this, []);
	}

	/**
	 * @param list<int|string> $path
	 * @return list<string>
	 */
	private function getNodeWarnings(self $node, array $path): array
	{
		$warningsByNode = [];
		$warningsByNode[] = $this->getPrefixedMessages($node->warnings, $path);

		foreach ($node->subNodes as $subPath => $subNode) {
			$warningsByNode[] = $this->getNodeWarnings($subNode, array_merge($path, [$subPath]));
		}

		return array_merge(...$warningsByNode);
	}

	/**
	 * @param list<string>     $messages
	 * @param list<int|string> $path
	 * @return list<string>
	 */
	private function getPrefixedMessages(array $messages, array $path): array
	{
		if ($path === []) {
			return $messages;
		}

		$pathString = implode(' > ', $path);
		foreach ($messages as &$message) {
			$message = "$pathString: $message";
		}

		return $messages;
	}

}
