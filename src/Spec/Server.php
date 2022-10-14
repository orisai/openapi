<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class Server implements SpecObject
{

	public string $url;

	public ?string $description = null;

	/** @var array<string, ServerVariable> */
	public array $variables = [];

	public function __construct(string $url)
	{
		$this->url = $url;
	}

	public function toArray(): array
	{
		$data = [
			'url' => $this->url,
		];

		if ($this->description !== null) {
			$data['description'] = $this->description;
		}

		if ($this->variables !== []) {
			$data['variables'] = SpecUtils::specsToArray($this->variables);
		}

		return $data;
	}

}