<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Spec;

use Orisai\Exceptions\Logic\InvalidArgument;
use Orisai\Exceptions\Message;
use Orisai\ObjectMapper\MappedObject;
use Orisai\ObjectMapper\Rules\AnyOf;
use Orisai\ObjectMapper\Rules\ArrayOf;
use Orisai\ObjectMapper\Rules\MappedObjectValue;
use Orisai\ObjectMapper\Rules\StringValue;
use Orisai\OpenAPI\Utils\SpecUtils;
use function preg_match;

final class Components implements SpecObject, MappedObject
{

	use SpecObjectSupportsExtensions;

	/**
	 * @var array<string, Schema|Reference>
	 *
	 * @ArrayOf(
	 *     item=@AnyOf({
	 *         @MappedObjectValue(AllOfSchema::class),
	 *         @MappedObjectValue(AnyOfSchema::class),
	 *         @MappedObjectValue(ArraySchema::class),
	 *         @MappedObjectValue(BoolSchema::class),
	 *         @MappedObjectValue(FloatSchema::class),
	 *         @MappedObjectValue(IntSchema::class),
	 *         @MappedObjectValue(NotSchema::class),
	 *         @MappedObjectValue(NullSchema::class),
	 *         @MappedObjectValue(ObjectSchema::class),
	 *         @MappedObjectValue(OneOfSchema::class),
	 *         @MappedObjectValue(StringSchema::class),
	 *         @MappedObjectValue(Reference::class),
	 *     }),
	 *     key=@StringValue(pattern="~^[a-zA-Z0-9\.\-_]+$~"),
	 * )
	 */
	private array $schemas = [];

	/**
	 * @var array<string, Response|Reference>
	 *
	 * @ArrayOf(
	 *     item=@AnyOf({
	 *         @MappedObjectValue(Response::class),
	 *         @MappedObjectValue(Reference::class),
	 *     }),
	 *     key=@StringValue(pattern="~^[a-zA-Z0-9\.\-_]+$~"),
	 * )
	 */
	private array $responses = [];

	/**
	 * @var array<string, Parameter|Reference>
	 *
	 * @ArrayOf(
	 *     item=@AnyOf({
	 *         @MappedObjectValue(Parameter::class),
	 *         @MappedObjectValue(Reference::class),
	 *     }),
	 *     key=@StringValue(pattern="~^[a-zA-Z0-9\.\-_]+$~"),
	 * )
	 */
	private array $parameters = [];

	/**
	 * @var array<string, Example|Reference>
	 *
	 * @ArrayOf(
	 *     item=@AnyOf({
	 *         @MappedObjectValue(Example::class),
	 *         @MappedObjectValue(Reference::class),
	 *     }),
	 *     key=@StringValue(pattern="~^[a-zA-Z0-9\.\-_]+$~"),
	 * )
	 */
	private array $examples = [];

	/**
	 * @var array<string, RequestBody|Reference>
	 *
	 * @ArrayOf(
	 *     item=@AnyOf({
	 *         @MappedObjectValue(RequestBody::class),
	 *         @MappedObjectValue(Reference::class),
	 *     }),
	 *     key=@StringValue(pattern="~^[a-zA-Z0-9\.\-_]+$~"),
	 * )
	 */
	private array $requestBodies = [];

	/**
	 * @var array<string, Header|Reference>
	 *
	 * @ArrayOf(
	 *     item=@AnyOf({
	 *         @MappedObjectValue(Header::class),
	 *         @MappedObjectValue(Reference::class),
	 *     }),
	 *     key=@StringValue(pattern="~^[a-zA-Z0-9\.\-_]+$~"),
	 * )
	 */
	private array $headers = [];

	/**
	 * @var array<string, SecurityScheme|Reference>
	 *
	 * @ArrayOf(
	 *     item=@AnyOf({
	 *         @MappedObjectValue(ApiKeySecurityScheme::class),
	 *         @MappedObjectValue(HttpSecurityScheme::class),
	 *         @MappedObjectValue(MutualTLSSecurityScheme::class),
	 *         @MappedObjectValue(OAuthSecurityScheme::class),
	 *         @MappedObjectValue(OpenIDConnectSecurityScheme::class),
	 *         @MappedObjectValue(Reference::class),
	 *     }),
	 *     key=@StringValue(pattern="~^[a-zA-Z0-9\.\-_]+$~"),
	 * )
	 */
	private array $securitySchemes = [];

	/**
	 * @var array<string, Link|Reference>
	 *
	 * @ArrayOf(
	 *     item=@AnyOf({
	 *         @MappedObjectValue(Link::class),
	 *         @MappedObjectValue(Reference::class),
	 *     }),
	 *     key=@StringValue(pattern="~^[a-zA-Z0-9\.\-_]+$~"),
	 * )
	 */
	private array $links = [];

	/**
	 * @var array<string, Callback|Reference>
	 *
	 * @ArrayOf(
	 *     item=@AnyOf({
	 *         @MappedObjectValue(Callback::class),
	 *         @MappedObjectValue(Reference::class),
	 *     }),
	 *     key=@StringValue(pattern="~^[a-zA-Z0-9\.\-_]+$~"),
	 * )
	 */
	private array $callbacks = [];

	/**
	 * @var array<string, PathItem|Reference>
	 *
	 * @ArrayOf(
	 *     item=@AnyOf({
	 *         @MappedObjectValue(PathItem::class),
	 *         @MappedObjectValue(Reference::class),
	 *     }),
	 *     key=@StringValue(pattern="~^[a-zA-Z0-9\.\-_]+$~"),
	 * )
	 */
	private array $pathItems = [];

	/**
	 * @param Schema|Reference $schema
	 */
	public function addSchema(string $key, $schema): void
	{
		$this->checkName($key, 'Schema');
		$this->schemas[$key] = $schema;
	}

	/**
	 * @return array<string, Schema|Reference>
	 */
	public function getSchemas(): array
	{
		return $this->schemas;
	}

	/**
	 * @param Response|Reference $response
	 */
	public function addResponse(string $key, $response): void
	{
		$this->checkName($key, 'Response');
		$this->responses[$key] = $response;
	}

	/**
	 * @return array<string, Response|Reference>
	 */
	public function getResponses(): array
	{
		return $this->responses;
	}

	/**
	 * @param Parameter|Reference $parameter
	 */
	public function addParameter(string $key, $parameter): void
	{
		$this->checkName($key, 'Parameter');
		$this->parameters[$key] = $parameter;
	}

	/**
	 * @return array<string, Parameter|Reference>
	 */
	public function getParameters(): array
	{
		return $this->parameters;
	}

	/**
	 * @param Example|Reference $example
	 */
	public function addExample(string $key, $example): void
	{
		$this->checkName($key, 'Example');
		$this->examples[$key] = $example;
	}

	/**
	 * @return array<string, Example|Reference>
	 */
	public function getExamples(): array
	{
		return $this->examples;
	}

	/**
	 * @param RequestBody|Reference $requestBody
	 */
	public function addRequestBody(string $key, $requestBody): void
	{
		$this->checkName($key, 'Request Body');
		$this->requestBodies[$key] = $requestBody;
	}

	/**
	 * @return array<string, RequestBody|Reference>
	 */
	public function getRequestBodies(): array
	{
		return $this->requestBodies;
	}

	/**
	 * @param Header|Reference $header
	 */
	public function addHeader(string $key, $header): void
	{
		$this->checkName($key, 'Header');
		$this->headers[$key] = $header;
	}

	/**
	 * @return array<string, Header|Reference>
	 */
	public function getHeaders(): array
	{
		return $this->headers;
	}

	/**
	 * @param SecurityScheme|Reference $securityScheme
	 */
	public function addSecurityScheme(string $key, $securityScheme): void
	{
		$this->checkName($key, 'Security Scheme');
		$this->securitySchemes[$key] = $securityScheme;
	}

	/**
	 * @return array<string, SecurityScheme|Reference>
	 */
	public function getSecuritySchemes(): array
	{
		return $this->securitySchemes;
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

	/**
	 * @param Callback|Reference $callback
	 */
	public function addCallback(string $key, $callback): void
	{
		$this->checkName($key, 'Callback');
		$this->callbacks[$key] = $callback;
	}

	/**
	 * @return array<string, Callback|Reference>
	 */
	public function getCallbacks(): array
	{
		return $this->callbacks;
	}

	/**
	 * @param PathItem|Reference $pathItem
	 */
	public function addPathItem(string $key, $pathItem): void
	{
		$this->checkName($key, 'Path Item');
		$this->pathItems[$key] = $pathItem;
	}

	/**
	 * @return array<string, PathItem|Reference>
	 */
	public function getPathItems(): array
	{
		return $this->pathItems;
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

	/**
	 * @return array<int|string, mixed>
	 */
	public function toRaw(): array
	{
		$data = [];

		if ($this->schemas !== []) {
			$data['schemas'] = SpecUtils::specsToArray($this->schemas);
		}

		if ($this->responses !== []) {
			$data['responses'] = SpecUtils::specsToArray($this->responses);
		}

		if ($this->parameters !== []) {
			$data['parameters'] = SpecUtils::specsToArray($this->parameters);
		}

		if ($this->examples !== []) {
			$data['examples'] = SpecUtils::specsToArray($this->examples);
		}

		if ($this->requestBodies !== []) {
			$data['requestBodies'] = SpecUtils::specsToArray($this->requestBodies);
		}

		if ($this->headers !== []) {
			$data['headers'] = SpecUtils::specsToArray($this->headers);
		}

		if ($this->schemas !== []) {
			$data['schemas'] = SpecUtils::specsToArray($this->schemas);
		}

		if ($this->securitySchemes !== []) {
			$data['securitySchemes'] = SpecUtils::specsToArray($this->securitySchemes);
		}

		if ($this->links !== []) {
			$data['links'] = SpecUtils::specsToArray($this->links);
		}

		if ($this->callbacks !== []) {
			$data['callbacks'] = SpecUtils::specsToArray($this->callbacks);
		}

		if ($this->pathItems !== []) {
			$data['pathItems'] = SpecUtils::specsToArray($this->pathItems);
		}

		$this->addExtensionsToData($data);

		return $data;
	}

}
