<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class NotSchema extends Schema
{

	/** @var Schema|Reference */
	private object $not;

	/**
	 * @param Schema|Reference $not
	 */
	public function __construct(object $not)
	{
		parent::__construct();
		$this->not = $not;
	}

	/**
	 * @return Schema|Reference
	 */
	public function getNot(): object
	{
		return $this->not;
	}

	public function toArray(): array
	{
		$data = parent::toArray();
		$data['not'] = $this->not->toArray();

		return $data;
	}

}
