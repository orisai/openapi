<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidState;
use Orisai\Exceptions\Message;
use Orisai\ObjectMapper\MappedObject;
use Orisai\ObjectMapper\Rules\MixedValue;
use Orisai\ObjectMapper\Rules\StringValue;
use ReflectionProperty;

final class Example implements SpecObject, MappedObject
{

	use SpecObjectChecksSerializableValue;
	use SpecObjectSupportsExtensions;

	/** @StringValue() */
	public ?string $summary = null;

	/** @StringValue() */
	public ?string $description = null;

	/**
	 * @var mixed
	 *
	 * @MixedValue()
	 */
	private $value;

	/** @StringValue() */
	private ?string $externalValue = null;

	public function __construct()
	{
		// TODO - call with object mapper
		unset($this->value);
	}

	/**
	 * @param mixed $value
	 */
	public function setValue($value): void
	{
		$this->checkSerializableValue($value, 'Example value');
		$this->externalValue = null;
		$this->value = $value;
	}

	public function hasValue(): bool
	{
		$property = new ReflectionProperty($this, 'value');
		$property->setAccessible(true);

		return $property->isInitialized($this);
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		if (!$this->hasValue()) {
			$message = Message::create()
				->withContext('Getting the Example value.')
				->withProblem('Value is not set and so cannot be get.')
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

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
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
