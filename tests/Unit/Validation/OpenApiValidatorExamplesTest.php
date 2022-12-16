<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Validation;

use Orisai\OpenAPI\Enum\ParameterIn;
use Orisai\OpenAPI\Spec\Callback;
use Orisai\OpenAPI\Spec\Encoding;
use Orisai\OpenAPI\Spec\Example;
use Orisai\OpenAPI\Spec\Header;
use Orisai\OpenAPI\Spec\Info;
use Orisai\OpenAPI\Spec\MediaType;
use Orisai\OpenAPI\Spec\OpenAPI;
use Orisai\OpenAPI\Spec\Operation;
use Orisai\OpenAPI\Spec\Parameter;
use Orisai\OpenAPI\Spec\PathItem;
use Orisai\OpenAPI\Spec\RequestBody;
use Orisai\OpenAPI\Spec\Response;
use Orisai\OpenAPI\Validation\OpenApiValidator;
use PHPUnit\Framework\TestCase;

final class OpenApiValidatorExamplesTest extends TestCase
{

	/**
	 * Way too lazy to solve it properly
	 */
	private bool $setExample = true;

	private bool $addExample = true;

	public function testForbiddenExampleCombination(): void
	{
		$validator = new OpenApiValidator();

		$openapi = $this->createOpenApi();
		$result = $validator->validate($openapi);

		self::assertFalse($result->isOk());
		self::assertSame(
			[],
			$result->getErrors(),
		);
		self::assertSame(
			require __DIR__ . '/OpenApiValidator.examples.warnings.php',
			$result->getWarnings(),
		);

		$this->setExample = false;
		$this->addExample = false;
		$openapi = $this->createOpenApi();
		$result = $validator->validate($openapi);
		self::assertTrue($result->isOk());
		self::assertSame([], $result->getErrors());
		self::assertSame([], $result->getWarnings());

		$this->setExample = true;
		$this->addExample = false;
		$openapi = $this->createOpenApi();
		$result = $validator->validate($openapi);
		self::assertTrue($result->isOk());
		self::assertSame([], $result->getErrors());
		self::assertSame([], $result->getWarnings());

		$this->setExample = false;
		$this->addExample = true;
		$openapi = $this->createOpenApi();
		$result = $validator->validate($openapi);
		self::assertTrue($result->isOk());
		self::assertSame([], $result->getErrors());
		self::assertSame([], $result->getWarnings());
	}

	private function createOpenApi(): OpenAPI
	{
		$openapi = new OpenAPI(new Info('title', 'version'));

		$components = $openapi->components;
		$components->addCallback('callback', $this->createCallback());
		$components->addHeader('header', $this->createHeader());
		$components->addPathItem('path', $this->createPathItem());
		$components->addParameter('parameter', $this->createParameter());
		$components->addRequestBody('body', $this->createRequestBody());
		$components->addResponse('response', $this->createResponse());

		$openapi->paths->addPath('/', $this->createPathItem());

		$openapi->addWebhook('/', $this->createPathItem());

		return $openapi;
	}

	private function createPathItem(bool $hasCallback = false): PathItem
	{
		$pathItem = new PathItem();

		// Just one callback is enough for test
		$pathItem->get = $this->createOperation($hasCallback);
		$pathItem->put = $this->createOperation(true);
		$pathItem->post = $this->createOperation(true);
		$pathItem->delete = $this->createOperation(true);
		$pathItem->options = $this->createOperation(true);
		$pathItem->head = $this->createOperation(true);
		$pathItem->patch = $this->createOperation(true);
		$pathItem->trace = $this->createOperation(true);

		$pathItem->addParameter($this->createParameter());

		return $pathItem;
	}

	private function createOperation(bool $hasCallback = false): Operation
	{
		$operation = new Operation();
		$operation->requestBody = $this->createRequestBody();
		$operation->responses->addResponse(200, $this->createResponse());
		$operation->addParameter($this->createParameter());
		if (!$hasCallback) {
			$operation->addCallback('callback', $this->createCallback());
		}

		return $operation;
	}

	private function createCallback(): Callback
	{
		$callback = new Callback();
		$callback->addExpression('expression', $this->createPathItem(true));

		return $callback;
	}

	private function createParameter(): Parameter
	{
		$parameter = new Parameter('name', ParameterIn::cookie());
		$this->addExamples($parameter);

		return $parameter;
	}

	private function createRequestBody(): RequestBody
	{
		$requestBody = new RequestBody();
		$requestBody->addContent('application/json', $this->createMediaType());

		return $requestBody;
	}

	private function createResponse(): Response
	{
		$response = new Response('description');
		$response->addContent('application/json', $this->createMediaType());
		$response->addHeader('header', $this->createHeader());

		return $response;
	}

	private function createMediaType(bool $hasHeader = false): MediaType
	{
		$mediaType = new MediaType();
		$this->addExamples($mediaType);

		$mediaType->addEncoding('a', $this->createEncoding($hasHeader));

		return $mediaType;
	}

	private function createEncoding(bool $hasHeader = false): Encoding
	{
		$encoding = new Encoding();

		if (!$hasHeader) {
			$encoding->addHeader('header', $this->createHeader());
		}

		return $encoding;
	}

	private function createHeader(): Header
	{
		$header = new Header();
		$this->addExamples($header);
		$header->addContent('application/json', $this->createMediaType(true));

		return $header;
	}

	/**
	 * @param MediaType|Parameter|Header $object
	 */
	private function addExamples(object $object): void
	{
		if ($this->setExample) {
			$object->setExample('example');
		}

		if ($this->addExample) {
			$object->addExample('a', new Example());
		}
	}

}
