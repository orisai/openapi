<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class License implements SpecObject
{

	public string $name;

	public ?string $identifier = null;

	public ?string $url = null;

	public function __construct(string $name)
	{
		$this->name = $name;
	}

	public function toArray(): array
	{
		$data = [
			'name' => $this->name,
		];

		if ($this->identifier !== null) {
			$data['identifier'] = $this->identifier;
		}

		if ($this->url !== null) {
			$data['url'] = $this->url;
		}

		return $data;
	}

}
