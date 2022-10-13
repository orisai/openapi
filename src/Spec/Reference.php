<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class Reference implements SpecObject
{

	public string $ref;

	public ?string $summary = null;

	public ?string $description = null;

	public function __construct(string $ref)
	{
		$this->ref = $ref;
	}

	public function toArray(): array
	{
		$data = [
			'$ref' => $this->ref,
		];

		if ($this->summary !== null) {
			$data['summary'] = $this->summary;
		}

		if ($this->description !== null) {
			$data['description'] = $this->description;
		}

		return $data;
	}

}
