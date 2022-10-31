<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Message;
use Orisai\OpenAPI\Enum\SecuritySchemeType;
use function strtolower;

final class HttpSecurityScheme extends SecurityScheme
{

	use SupportsSpecExtensions;

	/** @readonly */
	private string $scheme;

	private ?string $bearerFormat = null;

	public function __construct(string $scheme)
	{
		parent::__construct(SecuritySchemeType::http());
		$this->scheme = $scheme;
	}

	public function getScheme(): string
	{
		return $this->scheme;
	}

	public function setBearerFormat(string $format): void
	{
		if (strtolower($this->scheme) !== 'bearer') {
			$message = Message::create()
				->withContext("Setting a bearer format for security scheme '$this->scheme'.")
				->withProblem("Bearer format is supported only by scheme 'Bearer'.");

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
