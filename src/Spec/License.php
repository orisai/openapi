<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class License implements SpecObject
{

	use SupportsSpecExtensions;

	/** @readonly */
	public string $name;

	/** @readonly */
	public ?string $identifier;

	/** @readonly */
	public ?string $url;

	public function __construct(string $name, ?string $identifier = null, ?string $url = null)
	{
		$this->name = $name;
		$this->identifier = $identifier;
		$this->url = $url;
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

		$this->addExtensionsToData($data);

		return $data;
	}

}
