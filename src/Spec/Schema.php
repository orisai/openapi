<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidState;
use Orisai\Exceptions\Message;
use Orisai\ObjectMapper\MappedObject;
use Orisai\ObjectMapper\Modifiers\CreateWithoutConstructor;
use Orisai\ObjectMapper\Rules\BoolValue;
use Orisai\ObjectMapper\Rules\ListOf;
use Orisai\ObjectMapper\Rules\MappedObjectValue;
use Orisai\ObjectMapper\Rules\MixedValue;
use Orisai\ObjectMapper\Rules\StringValue;
use ReflectionProperty;

/**
 * @CreateWithoutConstructor()
 */
abstract class Schema implements SpecObject, MappedObject
{

	use SpecObjectChecksSerializableValue;
	use SpecObjectSupportsExtensions;

	/** @StringValue() */
	public ?string $title = null;

	/** @StringValue() */
	public ?string $description = null;

	/**
	 * @var list<mixed>
	 *
	 * @ListOf(@MixedValue())
	 */
	public array $enum = [];

	/** @StringValue() */
	public ?string $format = null;

	/**
	 * @var mixed
	 *
	 * @MixedValue()
	 */
	public $default;

	/** @BoolValue() */
	public bool $readOnly = false;

	/** @BoolValue() */
	public bool $writeOnly = false;

	/** @MappedObjectValue(XML::class) */
	public XML $xml;

	/** @MappedObjectValue(ExternalDocumentation::class) */
	public ?ExternalDocumentation $externalDocs = null;

	/**
	 * @var mixed
	 *
	 * @MixedValue()
	 */
	protected $example;

	/** @BoolValue() */
	public bool $deprecated = false;

	public function __construct()
	{
		$this->xml = new XML();
		unset($this->default, $this->example);
	}

	/**
	 * @param mixed $example
	 */
	public function setExample($example): void
	{
		$this->checkSerializableValue($example, 'Schema example');
		$this->example = $example;
	}

	public function hasExample(): bool
	{
		$property = new ReflectionProperty($this, 'example');
		$property->setAccessible(true);

		return $property->isInitialized($this);
	}

	/**
	 * @return mixed
	 */
	public function getExample()
	{
		if (!$this->hasExample()) {
			$message = Message::create()
				->withContext('Getting the Schema example.')
				->withProblem('Example is not set and so cannot be get.')
				->withSolution('Check with hasExample().');

			throw InvalidState::create()
				->withMessage($message);
		}

		return $this->example;
	}

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
	{
		$data = [];

		$xmlData = $this->xml->toRaw();
		if ($xmlData !== []) {
			$data['xml'] = $xmlData;
		}

		if ($this->externalDocs !== null) {
			$data['externalDocs'] = $this->externalDocs->toRaw();
		}

		if ($this->hasExample()) {
			$data['example'] = $this->example;
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
