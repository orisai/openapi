<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\ObjectMapper\MappedObject;
use Orisai\ObjectMapper\Rules\StringValue;

final class License implements SpecObject, MappedObject
{

	use SpecObjectSupportsExtensions;

	/**
	 * @readonly
	 *
	 * @StringValue()
	 */
	private string $name;

	/** @StringValue() */
	private ?string $identifier = null;

	/** @StringValue() */
	private ?string $url = null;

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

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
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
