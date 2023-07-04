<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Message;
use Orisai\ObjectMapper\Rules\ArrayOf;
use Orisai\ObjectMapper\Rules\MappedObjectValue;
use Orisai\ObjectMapper\Rules\StringValue;
use Orisai\OpenAPI\Utils\MediaTypes;

/**
 * @internal
 */
trait SpecObjectHasContent
{

	/**
	 * @var array<non-empty-string, MediaType>
	 *
	 * @ArrayOf(
	 *     item=@MappedObjectValue(MediaType::class),
	 *     key=@StringValue(notEmpty=true),
	 * )
	 */
	private array $content = [];

	/**
	 * @param non-empty-string $name
	 */
	public function addContent(string $name, MediaType $mediaType): void
	{
		if (!MediaTypes::isValid($name)) {
			$message = Message::create()
				->withContext("Adding a media type '$name'.")
				->withProblem('Type is not a valid media type or media type range.')
				->with(
					'Hint',
					'Validation is performed in compliance with https://www.rfc-editor.org/rfc/rfc2045#section-5.1 ' .
					'and https://www.rfc-editor.org/rfc/rfc7231#section-3.1.1.1',
				);

			throw InvalidArgument::create()
				->withMessage($message);
		}

		$this->content[MediaTypes::format($name)] = $mediaType;
	}

	/**
	 * @return array<non-empty-string, MediaType>
	 */
	public function getContent(): array
	{
		MediaTypes::sortTypesInKeys($this->content);

		return $this->content;
	}

}
