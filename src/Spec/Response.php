<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class Response implements SpecObject
{

	public string $description;

	/** @var array<string, Header|Reference> */
	public array $headers;

	/** @var array<string, MediaType> */
	public array $content;

	/** @var array<string, Link|Reference> */
	public array $links;

	public function toArray(): array
	{
		return [
			'description' => $this->description,
			'headers' => SpecUtils::specsToArray($this->headers),
			'content' => SpecUtils::specsToArray($this->content),
			'links' => SpecUtils::specsToArray($this->links),
		];
	}

}
