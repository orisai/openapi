<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidState;
use Orisai\Exceptions\Message;
use ReflectionProperty;

final class Example implements SpecObject
{

	use SpecObjectChecksExampleValue;
	use SpecObjectSupportsExtensions;

	public ?string $summary = null;

	public ?string $description = null;

	/** @var mixed */
	private $value;

	private ?string $externalValue = null;

	public function __construct()
	{
		unset($this->value);
	}

	/**
	 * @param mixed $value
	 */
	public function setValue($value): void
	{
		$this->checkExampleValue($value);
		$this->externalValue = null;
		$this->value = $value;
	}

	public function hasValue(): bool
	{
		return (new ReflectionProperty($this, 'value'))->isInitialized($this);
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		if (!$this->hasValue()) {
			$message = Message::create()
				->withContext('Getting the example value.')
				->withProblem('Example value is not set and so cannot be get.')
				->withSolution('Check with hasValue().');

			throw InvalidState::create()
				->withMessage($message);
		}

		return $this->value;
	}

	public function setExternalValue(string $externalValue): void
	{
		unset($this->value);
		$this->externalValue = $externalValue;
	}

	public function getExternalValue(): ?string
	{
		return $this->externalValue;
	}

	public function toArray(): array
	{
		$data = [];

		if ($this->summary !== null) {
			$data['summary'] = $this->summary;
		}

		if ($this->description !== null) {
			$data['description'] = $this->description;
		}

		if ($this->hasValue()) {
			$data['value'] = $this->value;
		}

		if ($this->externalValue !== null) {
			$data['externalValue'] = $this->externalValue;
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
