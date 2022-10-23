<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class Operation implements SpecObject
{

	/** @var list<string> */
	public array $tags = [];

	public ?string $summary = null;

	public ?string $description = null;

	public ?ExternalDocumentation $externalDocs = null;

	public ?string $operationId = null;

	/** @var list<Parameter|Reference> */
	public array $parameters = [];

	/** @var RequestBody|Reference|null */
	public $requestBody;

	public Responses $responses;

	/** @var array<string, Callback|Reference> */
	public array $callbacks = [];

	public bool $deprecated = false;

	/** @var list<SecurityRequirement> */
	public array $security = [];

	/** @var list<Server> */
	public array $servers = [];

	public function __construct()
	{
		$this->responses = new Responses();
	}

	public function toArray(): array
	{
		$data = [];

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
			$data['parameters'] = SpecUtils::specsToArray($this->parameters);
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

		if ($this->security !== []) {
			$data['security'] = SpecUtils::specsToArray($this->security);
		}

		if ($this->servers !== []) {
			$data['servers'] = SpecUtils::specsToArray($this->servers);
		}

		return $data;
	}

}
