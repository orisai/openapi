<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\OpenAPI\Utils\SpecUtils;

final class AllOfSchema extends Schema
{

	/** @var list<Schema|Reference> */
	private array $allOf;

	/**
	 * @param list<Schema|Reference> $allOf
	 */
	public function __construct(array $allOf)
	{
		parent::__construct();
		$this->allOf = $allOf;
	}

	/**
	 * @return list<Schema|Reference>
	 */
	public function getAllOf(): array
	{
		return $this->allOf;
	}

	public function toArray(): array
	{
		$data = parent::toArray();
		$data['allOf'] = SpecUtils::specsToArray($this->allOf);

		return $data;
	}

}
