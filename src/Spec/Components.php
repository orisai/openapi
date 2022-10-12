<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class Components implements SpecObject
{

	/** @var array<string, Schema|Reference> */
	public array $schemas;

	/** @var array<string, Response|Reference> */
	public array $responses;

	/** @var array<string, Parameter|Reference> */
	public array $parameters;

	/** @var array<string, Example|Reference> */
	public array $examples;

	/** @var array<string, RequestBody|Reference> */
	public array $requestBodies;

	/** @var array<string, Header|Reference> */
	public array $headers;

	/** @var array<string, SecurityScheme|Reference> */
	public array $securitySchemes;

	/** @var array<string, Link|Reference> */
	public array $links;

	/** @var array<string, Callback|Reference> */
	public array $callbacks;

	/** @var array<string, PathItem|Reference> */
	public array $pathItems;

	public function toArray(): array
	{
		return [
			'schemas' => SpecUtils::specsToArray($this->schemas),
			'responses' => SpecUtils::specsToArray($this->responses),
			'parameters' => SpecUtils::specsToArray($this->parameters),
			'examples' => SpecUtils::specsToArray($this->examples),
			'requestBodies' => SpecUtils::specsToArray($this->requestBodies),
			'headers' => SpecUtils::specsToArray($this->headers),
			'securitySchemes' => SpecUtils::specsToArray($this->securitySchemes),
			'links' => SpecUtils::specsToArray($this->links),
			'callbacks' => SpecUtils::specsToArray($this->callbacks),
			'pathItems' => SpecUtils::specsToArray($this->pathItems),
		];
	}

}
