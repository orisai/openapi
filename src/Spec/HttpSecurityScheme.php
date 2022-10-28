<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Message;
use Orisai\OpenAPI\Enum\SecuritySchemeType;

final class HttpSecurityScheme extends SecurityScheme
{

	use SupportsSpecExtensions;

	/** @readonly */
	public string $scheme;

	private ?string $bearerFormat = null;

	public function __construct(string $scheme)
	{
		parent::__construct(SecuritySchemeType::http());
		$this->scheme = $scheme;
	}

	public function setBearerFormat(string $format): void
	{
		//TODO - tests
		$bearerScheme = 'Bearer';
		if ($this->scheme !== $bearerScheme) {
			$message = Message::create()
				->withContext("Setting a bearer format for security scheme '$this->scheme'.")
				->withProblem("Bearer format is supported only by scheme '$bearerScheme'.");

			throw InvalidArgument::create()
				->withMessage($message);
		}

		$this->bearerFormat = $format;
	}

	public function toArray(): array
	{
		$data = parent::toArray();
		$data['scheme'] = $this->scheme;

		if ($this->bearerFormat !== null) {
			$data['bearerFormat'] = $this->bearerFormat;
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
