<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidState;
use Orisai\Exceptions\Message;
use Orisai\OpenAPI\Utils\SpecUtils;
use ReflectionProperty;

final class MediaType implements SpecObject
{

	use SpecObjectChecksSerializableValue;
	use SpecObjectSupportsExtensions;

	public ?Schema $schema = null;

	/** @var mixed */
	private $example;

	/** @var array<string, Example|Reference> */
	private array $examples = [];

	/** @var array<string, Encoding> */
	private array $encoding = [];

	public function __construct()
	{
		unset($this->example);
	}

	/**
	 * @param mixed $example
	 */
	public function setExample($example): void
	{
		$this->checkSerializableValue($example, 'MediaType example');
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
				->withContext('Getting the MediaType example.')
				->withProblem('Example is not set and so cannot be get.')
				->withSolution('Check with hasExample().');

			throw InvalidState::create()
				->withMessage($message);
		}

		return $this->example;
	}

	/**
	 * @param Example|Reference $example
	 */
	public function addExample(string $key, $example): void
	{
		$this->examples[$key] = $example;
	}

	/**
	 * @return array<string, Example|Reference>
	 */
	public function getExamples(): array
	{
		return $this->examples;
	}

	public function addEncoding(string $key, Encoding $encoding): void
	{
		$this->encoding[$key] = $encoding;
	}

	/**
	 * @return array<string, Encoding>
	 */
	public function getEncodings(): array
	{
		return $this->encoding;
	}

	public function toArray(): array
	{
		$data = [];

		if ($this->schema !== null) {
			$data['schema'] = $this->schema->toArray();
		}

		if ($this->hasExample()) {
			$data['example'] = $this->example;
		}

		if ($this->examples !== []) {
			$data['examples'] = SpecUtils::specsToArray($this->examples);
		}

		if ($this->encoding !== []) {
			$data['encoding'] = SpecUtils::specsToArray($this->encoding);
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
