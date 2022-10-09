<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

final class Responses
{

	/** @var Response|Reference|null */
	public $default;

	/** @var array<int, Response|Reference> */
	public $responses;

}
