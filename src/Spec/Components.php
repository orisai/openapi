<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class Components implements SpecObject
{

	/** @var array<string, Schema|Reference> */
	public array $schemas = [];

	/** @var array<string, Response|Reference> */
	public array $responses = [];

	/** @var array<string, Parameter|Reference> */
	public array $parameters = [];

	/** @var array<string, Example|Reference> */
	public array $examples = [];

	/** @var array<string, RequestBody|Reference> */
	public array $requestBodies = [];

	/** @var array<string, Header|Reference> */
	public array $headers = [];

	/** @var array<string, SecurityScheme|Reference> */
	public array $securitySchemes = [];

	/** @var array<string, Link|Reference> */
	public array $links = [];

	/** @var array<string, Callback|Reference> */
	public array $callbacks = [];

	/** @var array<string, PathItem|Reference> */
	public array $pathItems = [];

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

		return $data;
	}

}
