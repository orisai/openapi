<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;
use function array_merge;
use function array_values;
use function spl_object_id;

final class Operation implements SpecObject
{

	use SupportsSpecExtensions;

	/** @var list<string> */
	public array $tags = [];

	public ?string $summary = null;

	public ?string $description = null;

	public ?ExternalDocumentation $externalDocs = null;

	public ?string $operationId = null;

	/** @var array<int, Parameter|Reference> */
	private array $parameters = [];

	/** @var RequestBody|Reference|null */
	public $requestBody;

	public Responses $responses;

	/** @var array<string, Callback|Reference> */
	private array $callbacks = [];

	public bool $deprecated = false;

	/** @var array<int, SecurityRequirement> */
	private array $security = [];

	/** @var array<int, Server> */
	private array $servers = [];

	public function __construct()
	{
		$this->responses = new Responses();
	}

	/**
	 * @param Parameter|Reference $parameter
	 */
	public function addParameter($parameter): void
	{
		$this->parameters[spl_object_id($parameter)] = $parameter;
	}

	/**
	 * @return list<Parameter|Reference>
	 */
	public function getParameters(): array
	{
		return array_values($this->parameters);
	}

	/**
	 * @param Callback|Reference $callback
	 */
	public function addCallback(string $key, $callback): void
	{
		$this->callbacks[$key] = $callback;
	}

	/**
	 * @return array<string, Callback|Reference>
	 */
	public function getCallbacks(): array
	{
		return $this->callbacks;
	}

	public function addSecurityRequirement(SecurityRequirement $requirement): void
	{
		$this->security[spl_object_id($requirement)] = $requirement;
	}

	/**
	 * @return list<SecurityRequirement>
	 */
	public function getSecurityRequirements(): array
	{
		return array_values($this->security);
	}

	public function addServer(Server $server): void
	{
		$this->servers[spl_object_id($server)] = $server;
	}

	/**
	 * @return list<Server>
	 */
	public function getServers(): array
	{
		return array_values($this->servers);
	}

	public function toArray(): array
	{
		$data = [];

		//TODO - unikátní (není ve specifikaci)
		if ($this->tags !== []) {
			$data['tags'] = $this->tags;
		}

		if ($this->summary !== null) {
			$data['summary'] = $this->summary;
		}

		if ($this->description !== null) {
			$data['description'] = $this->description;
		}

		if ($this->externalDocs !== null) {
			$data['externalDocs'] = $this->externalDocs->toArray();
		}

		if ($this->operationId !== null) {
			$data['operationId'] = $this->operationId;
		}

		if ($this->parameters !== []) {
			$data['parameters'] = SpecUtils::specsToArray(array_values($this->parameters));
		}

		if ($this->requestBody !== null) {
			$data['requestBody'] = $this->requestBody->toArray();
		}

		$responsesData = $this->responses->toArray();
		if ($responsesData !== []) {
			$data['responses'] = $responsesData;
		}

		if ($this->callbacks !== []) {
			$data['callbacks'] = SpecUtils::specsToArray($this->callbacks);
		}

		if ($this->deprecated) {
			$data['deprecated'] = $this->deprecated;
		}

		//TODO - má dovolovat prázdné pole pro odstranění security z OpenAPI objektu
		// 		téhož se ale dá docílit i SecurityRequirement::createOptional()
		if ($this->security !== []) {
			$securityByObject = [];
			foreach ($this->security as $object) {
				$securityByObject[] = $object->toArray();
			}

			$data['security'] = array_merge(...$securityByObject);
		}

		if ($this->servers !== []) {
			$data['servers'] = SpecUtils::specsToArray(array_values($this->servers));
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
