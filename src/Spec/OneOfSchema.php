<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class OneOfSchema extends Schema
{

	/** @var list<Schema|Reference> */
	private array $oneOf;

	public ?Discriminator $discriminator = null;

	/**
	 * @param list<Schema|Reference> $oneOf
	 */
	public function __construct(array $oneOf)
	{
		parent::__construct();
		$this->oneOf = $oneOf;
	}

	/**
	 * @return list<Schema|Reference>
	 */
	public function getOneOf(): array
	{
		return $this->oneOf;
	}

	public function toArray(): array
	{
		$data = parent::toArray();
		$data['oneOf'] = SpecUtils::specsToArray($this->oneOf);

		if ($this->discriminator !== null) {
			$data['discriminator'] = $this->discriminator->toArray();
		}

		return $data;
	}

}
