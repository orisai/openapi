<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class Server implements SpecObject
{

	public string $url;

	public string $description;

	/** @var array<string, ServerVariable> */
	public array $variables;

	public function toArray(): array
	{
		return [
			'url' => $this->url,
			'description' => $this->description,
			'variables' => SpecUtils::specsToArray($this->variables),
		];
	}

}
