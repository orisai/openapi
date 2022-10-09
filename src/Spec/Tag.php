<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class Tag
{

	public string $name;

	public ?string $description;

	public ?ExternalDocumentation $externalDocs;

}
