<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Message;
use Orisai\ObjectMapper\Attributes\Expect\AnyOf;
use Orisai\ObjectMapper\Attributes\Expect\ArrayOf;
use Orisai\ObjectMapper\Attributes\Expect\MappedObjectValue;
use Orisai\ObjectMapper\Attributes\Expect\StringValue;
use Orisai\ObjectMapper\Attributes\Modifiers\CreateWithoutConstructor;
use Orisai\ObjectMapper\MappedObject;
use Orisai\OpenAPI\Utils\Headers;
use Orisai\OpenAPI\Utils\SpecUtils;
use function preg_match;

/**
 * @CreateWithoutConstructor()
 */
final class Response extends MappedObject implements SpecObject
{

	use SpecObjectHasContent;
	use SpecObjectSupportsExtensions;

	/** @StringValue() */
	public string $description;

	/**
	 * @var array<string, Header|Reference>
	 *
	 * @ArrayOf(
	 *     item=@AnyOf({
	 *         @MappedObjectValue(Header::class),
	 *         @MappedObjectValue(Reference::class),
	 *     }),
	 *     key=@StringValue(),
	 * )
	 */
	private array $headers = [];

	/**
	 * @var array<string, Link|Reference>
	 *
	 * @ArrayOf(
	 *     item=@AnyOf({
	 *         @MappedObjectValue(Link::class),
	 *         @MappedObjectValue(Reference::class),
	 *     }),
	 *     key=@StringValue(),
	 * )
	 */
	private array $links = [];

	public function __construct(string $description)
	{
		$this->description = $description;
	}

	/**
	 * @param Header|Reference $header
	 */
	public function addHeader(string $name, $header): void
	{
		if (!Headers::isNameValid($name)) {
			$message = Message::create()
				->withContext("Adding Response Header with name '$name'.")
				->withProblem('Name is not valid HTTP header name.')
				->with(
					'Hint',
					'Validation is performed in compliance with https://www.rfc-editor.org/rfc/rfc7230',
				);

			throw InvalidArgument::create()
				->withMessage($message);
		}

		$this->headers[Headers::formatName($name)] = $header;
	}

	/**
	 * @return array<string, Header|Reference>
	 */
	public function getHeaders(): array
	{
		return $this->headers;
	}

	/**
	 * @param Link|Reference $link
	 */
	public function addLink(string $key, $link): void
	{
		$this->checkName($key, 'Link');
		$this->links[$key] = $link;
	}

	/**
	 * @return array<string, Link|Reference>
	 */
	public function getLinks(): array
	{
		return $this->links;
	}

	private function checkName(string $key, string $specType): void
	{
		if (preg_match('~^[a-zA-Z0-9\.\-_]+$~', $key) === 1) {
			return;
		}

		$message = Message::create()
			->withContext("Assigning a spec object '$specType' with key '$key'.")
			->withProblem("Key must match regular expression '^[a-zA-Z0-9\.\-_]+\$'.");

		throw InvalidArgument::create()
			->withMessage($message);
	}

	public function toArray(): array
	{
		$data = [
			'description' => $this->description,
		];

		if ($this->headers !== []) {
			$data['headers'] = SpecUtils::specsToArray($this->headers);
		}

		$content = $this->getContent();
		if ($content !== []) {
			$data['content'] = SpecUtils::specsToArray($content);
		}

		if ($this->links !== []) {
			$data['links'] = SpecUtils::specsToArray($this->links);
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
