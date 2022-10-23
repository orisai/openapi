<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class Response implements SpecObject
{

	use SupportsSpecExtensions;

	public string $description;

	/** @var array<string, Header|Reference> */
	public array $headers = [];

	/** @var array<string, MediaType> */
	public array $content = [];

	/** @var array<string, Link|Reference> */
	public array $links = [];

	public function __construct(string $description)
	{
		$this->description = $description;
	}

	public function toArray(): array
	{
		$data = [
			'description' => $this->description,
		];

		if ($this->headers !== []) {
			$data['headers'] = SpecUtils::specsToArray($this->headers);
		}

		if ($this->content !== []) {
			$data['content'] = SpecUtils::specsToArray($this->content);
		}

		if ($this->links !== []) {
			$data['links'] = SpecUtils::specsToArray($this->links);
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
