<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Message;
use Orisai\OpenAPI\Utils\SpecUtils;
use function ksort;
use function preg_match;
use const SORT_STRING;

final class RequestBody implements SpecObject
{

	use SupportsSpecExtensions;

	public ?string $description = null;

	/** @var array<string, MediaType> */
	private array $content = [];

	public bool $required = false;

	/**
	 * @param non-empty-array<string, MediaType> $content
	 */
	public function __construct(array $content)
	{
		foreach ($content as $name => $mediaType) {
			$this->addContent($name, $mediaType);
		}
	}

	public function addContent(string $name, MediaType $mediaType): void
	{
		//TODO - tests
		if (preg_match(
			'@' .
				"^(application|audio|font|example|image|message|model|multipart|text|video|x-(?:[0-9A-Za-z!#$%&'*+.^_`|~-]+))" .
				'/' .
				"([0-9A-Za-z!#$%&'*+.^_`|~-]+)" .
				"((?:[ \t]*;[ \t]*[0-9A-Za-z!#$%&'*+.^_`|~-]+=(?:[0-9A-Za-z!#$%&'*+.^_`|~-]+|\"(?:[^\"\\\\]|\\.)*\"))*)" .
				'$@',
			$name,
		) !== 1) {
			$message = Message::create()
				->withContext("Adding a media type '$name'.")
				->withProblem('Type is not a valid media type.')
				->with(
					'Hint',
					'Validation is performed in compliance with https://www.rfc-editor.org/rfc/rfc2045#section-5.1' .
						'and https://www.rfc-editor.org/rfc/rfc7231#section-3.1.1.1',
				);

			throw InvalidArgument::create()
				->withMessage($message);
		}

		$this->content[$name] = $mediaType;
	}

	/**
	 * @return array<string, MediaType>
	 */
	public function getContent(): array
	{
		//TODO - tests
		ksort($this->content, SORT_STRING);

		return $this->content;
	}

	public function toArray(): array
	{
		//TODO - je required - má být tedy alespoň jeden??
		$data = [
			'content' => SpecUtils::specsToArray($this->getContent()),
		];

		if ($this->description !== null) {
			$data['description'] = $this->description;
		}

		if ($this->required) {
			$data['required'] = $this->required;
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
