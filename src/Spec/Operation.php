<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;
use function array_merge;

final class Operation implements SpecObject
{

	use SupportsSpecExtensions;

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

	/** @readonly */
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

		//TODO - parametry nesmí být duplicitní (kombinace name a location)
		//		- musí brát v úvahu reference

		//TODO - přepisuje parametry z cesty
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
			$data['servers'] = SpecUtils::specsToArray($this->servers);
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
