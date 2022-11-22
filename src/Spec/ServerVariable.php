<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Message;
use function implode;
use function in_array;

final class ServerVariable implements SpecObject
{

	use SpecObjectSupportsExtensions;

	/**
	 * @var non-empty-array<string>|null
	 * @readonly
	 */
	private ?array $enum;

	/** @readonly */
	private string $default;

	public ?string $description = null;

	/**
	 * @param non-empty-array<string>|null $enum
	 */
	public function __construct(string $default, ?array $enum = null)
	{
		$this->default = $default;
		$this->enum = $enum;

		if ($enum !== null && !in_array($default, $enum, true)) {
			$casesInline = implode("', '", $enum);
			$message = Message::create()
				->withContext("Creating a ServerVariable with default value '$default'.")
				->withProblem("Default variable is not listed in given enum cases '$casesInline'.")
				->withSolution('Add default to enum or don\'t use enum.');

			throw InvalidArgument::create()
				->withMessage($message);
		}
	}

	public function getDefault(): string
	{
		return $this->default;
	}

	/**
	 * @return non-empty-array<string>|null
	 */
	public function getEnum(): ?array
	{
		return $this->enum;
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

		$this->addExtensionsToData($data);

		return $data;
	}

}
