<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidState;
use Orisai\Exceptions\Message;
use ReflectionProperty;

abstract class Schema implements SpecObject
{

	use SpecObjectChecksSerializableValue;
	use SpecObjectSupportsExtensions;

	public ?string $title = null;

	public ?string $description = null;

	/** @var list<mixed> */
	public array $enum = [];

	public string $format;

	/** @var mixed */
	public $default;

	public bool $readOnly = false;

	public bool $writeOnly = false;

	public XML $xml;

	public ?ExternalDocumentation $externalDocs = null;

	/** @var mixed */
	protected $example;

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
		return (new ReflectionProperty($this, 'example'))->isInitialized($this);
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

	public function toArray(): array
	{
		$data = [];

		$xmlData = $this->xml->toArray();
		if ($xmlData !== []) {
			$data['xml'] = $xmlData;
		}

		if ($this->externalDocs !== null) {
			$data['externalDocs'] = $this->externalDocs->toArray();
		}

		if ($this->hasExample()) {
			$data['example'] = $this->example;
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
