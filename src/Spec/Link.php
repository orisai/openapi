<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidState;
use Orisai\Exceptions\Message;
use Orisai\ObjectMapper\MappedObject;
use Orisai\ObjectMapper\Rules\ArrayOf;
use Orisai\ObjectMapper\Rules\MappedObjectValue;
use Orisai\ObjectMapper\Rules\MixedValue;
use Orisai\ObjectMapper\Rules\StringValue;
use ReflectionProperty;

final class Link implements SpecObject, MappedObject
{

	use SpecObjectChecksSerializableValue;
	use SpecObjectSupportsExtensions;

	/** @StringValue() */
	private ?string $operationRef = null;

	/** @StringValue() */
	private ?string $operationId = null;

	/**
	 * @var array<string, mixed>
	 *
	 * @ArrayOf(
	 *     item=@MixedValue(),
	 *     key=@StringValue(),
	 * )
	 */
	private array $parameters = [];

	/**
	 * @var mixed
	 *
	 * @MixedValue()
	 */
	private $requestBody;

	/** @StringValue() */
	public ?string $description = null;

	/** @MappedObjectValue(Server::class) */
	public ?Server $server = null;

	private function __construct()
	{
		// TODO - nevalidní kombinace (viz statické konstruktory)
		//TODO - call with object mapper
		unset($this->requestBody);
	}

	public static function forId(string $operationId): self
	{
		$self = new self();
		$self->operationId = $operationId;

		return $self;
	}

	public static function forRef(string $operationRef): self
	{
		$self = new self();
		$self->operationRef = $operationRef;

		return $self;
	}

	public function getOperationId(): ?string
	{
		return $this->operationId;
	}

	public function getOperationRef(): ?string
	{
		return $this->operationRef;
	}

	/**
	 * @param mixed $requestBody
	 */
	public function setRequestBody($requestBody): void
	{
		$this->checkSerializableValue($requestBody, 'Link requestBody');
		$this->requestBody = $requestBody;
	}

	public function hasRequestBody(): bool
	{
		$property = new ReflectionProperty($this, 'requestBody');
		$property->setAccessible(true);

		return $property->isInitialized($this);
	}

	/**
	 * @return mixed
	 */
	public function getRequestBody()
	{
		if (!$this->hasRequestBody()) {
			$message = Message::create()
				->withContext('Getting the Link requestBody.')
				->withProblem('RequestBody is not set and so cannot be get.')
				->withSolution('Check with hasRequestBody().');

			throw InvalidState::create()
				->withMessage($message);
		}

		return $this->requestBody;
	}

	/**
	 * @param mixed $value
	 */
	public function addParameter(string $name, $value): void
	{
		$this->parameters[$name] = $value;
	}

	/**
	 * @return array<string, mixed>
	 */
	public function getParameters(): array
	{
		return $this->parameters;
	}

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
	{
		$data = [];

		if ($this->operationRef !== null) {
			$data['operationRef'] = $this->operationRef;
		}

		if ($this->operationId !== null) {
			$data['operationId'] = $this->operationId;
		}

		if ($this->parameters !== []) {
			$data['parameters'] = $this->parameters;
		}

		if ($this->hasRequestBody()) {
			$data['requestBody'] = $this->requestBody;
		}

		if ($this->description !== null) {
			$data['description'] = $this->description;
		}

		if ($this->server !== null) {
			$data['server'] = $this->server->toRaw();
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
