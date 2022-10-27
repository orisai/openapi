<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Message;
use Orisai\OpenAPI\Utils\SpecUtils;
use function preg_match;

final class Components implements SpecObject
{

	use SupportsSpecExtensions;

	/** @var array<string, Schema|Reference> */
	private array $schemas = [];

	/** @var array<string, Response|Reference> */
	private array $responses = [];

	/** @var array<string, Parameter|Reference> */
	private array $parameters = [];

	/** @var array<string, Example|Reference> */
	private array $examples = [];

	/** @var array<string, RequestBody|Reference> */
	private array $requestBodies = [];

	/** @var array<string, Header|Reference> */
	private array $headers = [];

	/** @var array<string, SecurityScheme|Reference> */
	private array $securitySchemes = [];

	/** @var array<string, Link|Reference> */
	private array $links = [];

	/** @var array<string, Callback|Reference> */
	private array $callbacks = [];

	/** @var array<string, PathItem|Reference> */
	private array $pathItems = [];

	/**
	 * @param Schema|Reference $schema
	 */
	public function addSchema(string $key, $schema): void
	{
		$this->checkName($key, 'Schema');
		$this->schemas[$key] = $schema;
	}

	/**
	 * @param Response|Reference $response
	 */
	public function addResponse(string $key, $response): void
	{
		$this->checkName($key, 'Response');
		$this->responses[$key] = $response;
	}

	/**
	 * @param Parameter|Reference $parameter
	 */
	public function addParameter(string $key, $parameter): void
	{
		$this->checkName($key, 'Parameter');
		$this->parameters[$key] = $parameter;
	}

	/**
	 * @param Example|Reference $example
	 */
	public function addExample(string $key, $example): void
	{
		$this->checkName($key, 'Example');
		$this->examples[$key] = $example;
	}

	/**
	 * @param RequestBody|Reference $requestBody
	 */
	public function addRequestBody(string $key, $requestBody): void
	{
		$this->checkName($key, 'RequestBody');
		$this->requestBodies[$key] = $requestBody;
	}

	/**
	 * @param Header|Reference $header
	 */
	public function addHeader(string $key, $header): void
	{
		$this->checkName($key, 'Header');
		$this->headers[$key] = $header;
	}

	/**
	 * @param SecurityScheme|Reference $securityScheme
	 */
	public function addSecurityScheme(string $key, $securityScheme): void
	{
		$this->checkName($key, 'SecurityScheme');
		$this->securitySchemes[$key] = $securityScheme;
	}

	/**
	 * @param Link|Reference $link
	 */
	public function addLink(string $key, $link): void
	{
		$this->checkName($key, 'Link');
		$this->links[$key] = $link;
	}

	/**
	 * @param Callback|Reference $callback
	 */
	public function addCallback(string $key, $callback): void
	{
		$this->checkName($key, 'Callback');
		$this->callbacks[$key] = $callback;
	}

	/**
	 * @param PathItem|Reference $pathItem
	 */
	public function addPathItem(string $key, $pathItem): void
	{
		$this->checkName($key, 'PathItem');
		$this->pathItems[$key] = $pathItem;
	}

	private function checkName(string $key, string $specType): void
	{
		//TODO - otestovat validace
		if (preg_match('~^[a-zA-Z0-9\.\-_]+$~', $key) === 1) {
			return;
		}

		$message = Message::create()
			->withContext("Assigning a spec object '$specType' with key '$key'.")
			->withProblem("Key must match regular expression '^[a-zA-Z0-9\.\-_]+\$'.");

		throw InvalidArgument::create()
			->withMessage($message);
	}

	public function toArray(): array
	{
		$data = [];

		if ($this->schemas !== []) {
			$data['schemas'] = SpecUtils::specsToArray($this->schemas);
		}

		if ($this->responses !== []) {
			$data['responses'] = SpecUtils::specsToArray($this->responses);
		}

		if ($this->parameters !== []) {
			$data['parameters'] = SpecUtils::specsToArray($this->parameters);
		}

		if ($this->examples !== []) {
			$data['examples'] = SpecUtils::specsToArray($this->examples);
		}

		if ($this->requestBodies !== []) {
			$data['requestBodies'] = SpecUtils::specsToArray($this->requestBodies);
		}

		if ($this->headers !== []) {
			$data['headers'] = SpecUtils::specsToArray($this->headers);
		}

		if ($this->schemas !== []) {
			$data['schemas'] = SpecUtils::specsToArray($this->schemas);
		}

		if ($this->securitySchemes !== []) {
			$data['securitySchemes'] = SpecUtils::specsToArray($this->securitySchemes);
		}

		if ($this->links !== []) {
			$data['links'] = SpecUtils::specsToArray($this->links);
		}

		if ($this->callbacks !== []) {
			$data['callbacks'] = SpecUtils::specsToArray($this->callbacks);
		}

		if ($this->pathItems !== []) {
			$data['pathItems'] = SpecUtils::specsToArray($this->pathItems);
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
