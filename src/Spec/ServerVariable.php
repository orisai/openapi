<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class ServerVariable implements SpecObject
{

	/** @var non-empty-array<string>|null */
	public ?array $enum = null;

	public string $default;

	public ?string $description = null;

	public function __construct(string $default)
	{
		$this->default = $default;
	}

	public function toArray(): array
	{
		$data = [
			'default' => $this->default,
		];

		if ($this->enum !== null) {
			$data['enum'] = $this->enum;
		}

		if ($this->description !== null) {
			$data['description'] = $this->description;
		}

		return $data;
	}

}
