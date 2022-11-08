<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class Tag implements SpecObject
{

	use SpecObjectSupportsExtensions;

	/** @readonly */
	private string $name;

	public ?string $description = null;

	public ?ExternalDocumentation $externalDocs = null;

	public function __construct(string $name)
	{
		$this->name = $name;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function toArray(): array
	{
		//TODO - v nadřazené komponentě unikátní (check spec)
		$data = [
			'name' => $this->name,
		];

		if ($this->description !== null) {
			$data['description'] = $this->description;
		}

		if ($this->externalDocs !== null) {
			$data['externalDocs'] = $this->externalDocs->toArray();
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
