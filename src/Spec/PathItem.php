<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class PathItem implements SpecObject
{

	use SupportsSpecExtensions;

	public ?string $ref = null;

	public ?string $summary = null;

	public ?string $description = null;

	public ?Operation $get = null;

	public ?Operation $put = null;

	public ?Operation $post = null;

	public ?Operation $delete = null;

	public ?Operation $options = null;

	public ?Operation $head = null;

	public ?Operation $patch = null;

	public ?Operation $trace = null;

	/** @var list<Server> */
	public array $servers = [];

	/** @var list<Parameter|Reference> */
	public array $parameters = [];

	public function toArray(): array
	{
		$data = [];

		//TODO - PathItem může být empty, v závislosti na ACL - https://spec.openapis.org/oas/v3.1.0#securityFiltering

		//TODO - tam kde se cesta používá (OpenApi, Callbacks, Paths)
		//			by se mělo validovat, že jsou dostupné všechny templated parametry
		//		- brát v úvahu parametry z operace
		//		- a že jsou v cestě (query?)
		//		- dovoluje openapi query?
		//		- ve specifikaci toto není?

		//TODO - jak resolvnout reference?
		//	- změna summary a description je ok, přidání serverů asi taky, změna operací a parametrů je problém
		//	- https://github.com/cebe/php-openapi/blob/master/src/spec/PathItem.php#L153
		//	- https://github.com/OAI/OpenAPI-Specification/issues/1038

		//TODO - parametry nesmí být duplicitní (kombinace name a location)
		//		- musí brát v úvahu reference
		if ($this->ref !== null) {
			$data['$ref'] = $this->ref;
		}

		if ($this->summary !== null) {
			$data['summary'] = $this->summary;
		}

		if ($this->description !== null) {
			$data['description'] = $this->description;
		}

		if ($this->get !== null) {
			$data['get'] = $this->get->toArray();
		}

		if ($this->put !== null) {
			$data['put'] = $this->put->toArray();
		}

		if ($this->post !== null) {
			$data['post'] = $this->post->toArray();
		}

		if ($this->delete !== null) {
			$data['delete'] = $this->delete->toArray();
		}

		if ($this->options !== null) {
			$data['options'] = $this->options->toArray();
		}

		if ($this->head !== null) {
			$data['head'] = $this->head->toArray();
		}

		if ($this->patch !== null) {
			$data['patch'] = $this->patch->toArray();
		}

		if ($this->trace !== null) {
			$data['trace'] = $this->trace->toArray();
		}

		if ($this->servers !== []) {
			$data['servers'] = SpecUtils::specsToArray($this->servers);
		}

		if ($this->parameters !== []) {
			$data['parameters'] = SpecUtils::specsToArray($this->parameters);
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
