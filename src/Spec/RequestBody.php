<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class RequestBody implements SpecObject
{

	public ?string $description;

	/** @var array<string, MediaType> */
	public array $content;

	public bool $required;

	public function toArray(): array
	{
		return [
			'description' => $this->description,
			'content' => SpecUtils::specsToArray($this->content),
			'required' => $this->required,
		];
	}

}
