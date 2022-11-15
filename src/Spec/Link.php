<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidState;
use Orisai\Exceptions\Message;
use ReflectionProperty;

final class Link implements SpecObject
{

	use SpecObjectChecksSerializableValue;
	use SpecObjectSupportsExtensions;

	private ?string $operationRef = null;

	private ?string $operationId = null;

	/** @var array<string, mixed> */
	private array $parameters = [];

	/** @var mixed */
	private $requestBody;

	public ?string $description = null;

	public ?Server $server = null;

	private function __construct()
	{
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
		return (new ReflectionProperty($this, 'requestBody'))->isInitialized($this);
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

	public function toArray(): array
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
			$data['server'] = $this->server->toArray();
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
