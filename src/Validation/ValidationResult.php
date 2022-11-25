<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Validation;

final class ValidationResult
{

	/** @var list<string> */
	private array $errors;

	/** @var list<string> */
	private array $warnings;

	/**
	 * @param list<string> $errors
	 * @param list<string> $warnings
	 */
	public function __construct(array $errors, array $warnings)
	{
		$this->errors = $errors;
		$this->warnings = $warnings;
	}

	/**
	 * @return list<string>
	 */
	public function getErrors(): array
	{
		return $this->errors;
	}

	/**
	 * @return list<string>
	 */
	public function getWarnings(): array
	{
		return $this->warnings;
	}

	public function isOk(): bool
	{
		return $this->errors === []
			&& $this->warnings === [];
	}

}
