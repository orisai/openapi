<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class ObjectSchema extends Schema
{

	private string $type;

	/** @var array<Schema|Reference> */
	public array $properties = [];

	/** @var list<string> */
	public array $requiredProperties = [];

	/** @var int<0, max>|null */
	public ?int $minProperties = null;

	/** @var int<0, max>|null */
	public ?int $maxProperties = null;

	/** @var Schema|Reference|bool */
	public $additionalProperties = true;

	public ?Discriminator $discriminator = null;

	public function __construct()
	{
		parent::__construct();
		$this->type = 'object';
	}

	public function toArray(): array
	{
		$data = parent::toArray();
		$data['type'] = $this->type;

		if ($this->properties !== []) {
			$data['properties'] = SpecUtils::specsToArray($this->properties);
		}

		if ($this->requiredProperties !== []) {
			$data['required'] = $this->requiredProperties;
		}

		if ($this->minProperties !== null) {
			$data['minProperties'] = $this->minProperties;
		}

		if ($this->maxProperties !== null) {
			$data['maxProperties'] = $this->maxProperties;
		}

		if ($this->additionalProperties !== true) {
			$data['additionalProperties'] = $this->additionalProperties;
		}

		if ($this->discriminator !== null) {
			$data['discriminator'] = $this->discriminator->toArray();
		}

		return $data;
	}

}
