<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class AnyOfSchema extends Schema
{

	/** @var list<Schema|Reference> */
	private array $anyOf;

	public ?Discriminator $discriminator = null;

	/**
	 * @param list<Schema|Reference> $anyOf
	 */
	public function __construct(array $anyOf)
	{
		parent::__construct();
		$this->anyOf = $anyOf;
	}

	/**
	 * @return list<Schema|Reference>
	 */
	public function getAnyOf(): array
	{
		return $this->anyOf;
	}

	public function toArray(): array
	{
		$data = parent::toArray();
		$data['anyOf'] = SpecUtils::specsToArray($this->anyOf);

		if ($this->discriminator !== null) {
			$data['discriminator'] = $this->discriminator->toArray();
		}

		return $data;
	}

}
