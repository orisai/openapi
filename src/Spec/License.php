<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class License implements SpecObject
{

	use SpecObjectSupportsExtensions;

	/** @readonly */
	private string $name;

	/** @readonly */
	private ?string $identifier;

	/** @readonly */
	private ?string $url;

	public function __construct(string $name, ?string $identifier = null, ?string $url = null)
	{
		$this->name = $name;
		$this->identifier = $identifier;
		$this->url = $url;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getIdentifier(): ?string
	{
		return $this->identifier;
	}

	public function getUrl(): ?string
	{
		return $this->url;
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
