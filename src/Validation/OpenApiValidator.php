<?php declare(strict_types = 1);

namespace Orisai\OpenAPI\Validation;

use Orisai\OpenAPI\Spec\Callback;
use Orisai\OpenAPI\Spec\Components;
use Orisai\OpenAPI\Spec\Encoding;
use Orisai\OpenAPI\Spec\Header;
use Orisai\OpenAPI\Spec\MediaType;
use Orisai\OpenAPI\Spec\OpenAPI;
use Orisai\OpenAPI\Spec\Operation;
use Orisai\OpenAPI\Spec\Parameter;
use Orisai\OpenAPI\Spec\PathItem;
use Orisai\OpenAPI\Spec\Paths;
use Orisai\OpenAPI\Spec\Reference;
use Orisai\OpenAPI\Spec\RequestBody;
use Orisai\OpenAPI\Spec\Response;
use Orisai\OpenAPI\Spec\Responses;

final class OpenApiValidator
{

	public function validate(OpenAPI $openAPI): ValidationResult
	{
		return $this->inspectOpenApi($openAPI);
	}

	private function inspectOpenApi(OpenAPI $openAPI): ValidationResult
	{
		$node = new ValidationNode();

		$this->inspectComponents($openAPI->components, $node->getSubNode('components'));

		$this->inspectPaths($openAPI->paths, $node->getSubNode('paths'));

		$webhooksNode = $node->getSubNode('webhooks');
		foreach ($openAPI->getWebhooks() as $key => $webhook) {
			if ($webhook instanceof Reference) {
				continue;
			}

			$this->inspectPathItem($webhook, $webhooksNode->getSubNode($key));
		}

		return new ValidationResult($node->getAllErrors(), $node->getAllWarnings());
	}

	private function inspectComponents(Components $components, ValidationNode $node): void
	{
		$callbacksNode = $node->getSubNode('callbacks');
		foreach ($components->getCallbacks() as $key => $callback) {
			if ($callback instanceof Reference) {
				continue;
			}

			$this->inspectCallback($callback, $callbacksNode->getSubNode($key));
		}

		$headersNode = $node->getSubNode('headers');
		foreach ($components->getHeaders() as $key => $header) {
			if ($header instanceof Reference) {
				continue;
			}

			$this->inspectHeader($header, $headersNode->getSubNode($key));
		}

		$pathItemsNode = $node->getSubNode('pathItems');
		foreach ($components->getPathItems() as $key => $pathItem) {
			if ($pathItem instanceof Reference) {
				continue;
			}

			$this->inspectPathItem($pathItem, $pathItemsNode->getSubNode($key));
		}

		$parametersNode = $node->getSubNode('parameters');
		foreach ($components->getParameters() as $key => $parameter) {
			if ($parameter instanceof Reference) {
				continue;
			}

			$this->inspectParameter($parameter, $parametersNode->getSubNode($key));
		}

		$requestBodiesNode = $node->getSubNode('requestBodies');
		foreach ($components->getRequestBodies() as $key => $requestBody) {
			if ($requestBody instanceof Reference) {
				continue;
			}

			$this->inspectRequestBody($requestBody, $requestBodiesNode->getSubNode($key));
		}

		$responsesNode = $node->getSubNode('responses');
		foreach ($components->getResponses() as $key => $response) {
			if ($response instanceof Reference) {
				continue;
			}

			$this->inspectResponse($response, $responsesNode->getSubNode($key));
		}
	}

	private function inspectCallback(Callback $callback, ValidationNode $node): void
	{
		foreach ($callback->getExpressions() as $expression => $pathItem) {
			if ($pathItem instanceof Reference) {
				continue;
			}

			$this->inspectPathItem($pathItem, $node->getSubNode($expression));
		}
	}

	private function inspectEncoding(Encoding $encoding, ValidationNode $node): void
	{
		$headersNode = $node->getSubNode('headers');
		foreach ($encoding->getHeaders() as $key => $header) {
			if ($header instanceof Reference) {
				continue;
			}

			$this->inspectHeader($header, $headersNode->getSubNode($key));
		}
	}

	private function inspectHeader(Header $header, ValidationNode $node): void
	{
		$this->checkHeaderExamples($header, $node);

		$contentNode = $node->getSubNode('content');
		foreach ($header->getContent() as $key => $mediaType) {
			$this->inspectMediaType($mediaType, $contentNode->getSubNode($key));
		}
	}

	private function checkHeaderExamples(Header $header, ValidationNode $node): void
	{
		if ($header->hasExample() && $header->getExamples() !== []) {
			$node->addWarning("Both 'example' and 'examples' are defined, only one is allowed.");
		}
	}

	private function inspectMediaType(MediaType $mediaType, ValidationNode $node): void
	{
		$this->checkMediaTypeExamples($mediaType, $node);

		$encodingsNode = $node->getSubNode('encoding');
		foreach ($mediaType->getEncodings() as $key => $encoding) {
			$this->inspectEncoding($encoding, $encodingsNode->getSubNode($key));
		}
	}

	private function checkMediaTypeExamples(MediaType $mediaType, ValidationNode $node): void
	{
		if ($mediaType->hasExample() && $mediaType->getExamples() !== []) {
			$node->addWarning("Both 'example' and 'examples' are defined, only one is allowed.");
		}
	}

	private function inspectOperation(Operation $operation, ValidationNode $node): void
	{
		$callbacksNode = $node->getSubNode('callbacks');
		foreach ($operation->getCallbacks() as $key => $callback) {
			if ($callback instanceof Reference) {
				continue;
			}

			$this->inspectCallback($callback, $callbacksNode->getSubNode($key));
		}

		$parametersNode = $node->getSubNode('parameters');
		foreach ($operation->getParameters() as $key => $parameter) {
			if ($parameter instanceof Reference) {
				continue;
			}

			$this->inspectParameter($parameter, $parametersNode->getSubNode($key));
		}

		$requestBody = $operation->requestBody;
		if ($requestBody !== null && !$requestBody instanceof Reference) {
			$this->inspectRequestBody($requestBody, $node->getSubNode('requestBody'));
		}

		$this->inspectResponses($operation->responses, $node->getSubNode('responses'));
	}

	private function inspectPaths(Paths $paths, ValidationNode $node): void
	{
		foreach ($paths->getPaths() as $path => $pathItem) {
			$this->inspectPathItem($pathItem, $node->getSubNode($path));
		}
	}

	private function inspectPathItem(PathItem $pathItem, ValidationNode $node): void
	{
		if ($pathItem->get !== null) {
			$this->inspectOperation($pathItem->get, $node->getSubNode('get'));
		}

		if ($pathItem->put !== null) {
			$this->inspectOperation($pathItem->put, $node->getSubNode('put'));
		}

		if ($pathItem->post !== null) {
			$this->inspectOperation($pathItem->post, $node->getSubNode('post'));
		}

		if ($pathItem->delete !== null) {
			$this->inspectOperation($pathItem->delete, $node->getSubNode('delete'));
		}

		if ($pathItem->options !== null) {
			$this->inspectOperation($pathItem->options, $node->getSubNode('options'));
		}

		if ($pathItem->head !== null) {
			$this->inspectOperation($pathItem->head, $node->getSubNode('head'));
		}

		if ($pathItem->patch !== null) {
			$this->inspectOperation($pathItem->patch, $node->getSubNode('patch'));
		}

		if ($pathItem->trace !== null) {
			$this->inspectOperation($pathItem->trace, $node->getSubNode('trace'));
		}

		$parametersNode = $node->getSubNode('parameters');
		foreach ($pathItem->getParameters() as $key => $parameter) {
			if ($parameter instanceof Reference) {
				continue;
			}

			$this->inspectParameter($parameter, $parametersNode->getSubNode($key));
		}
	}

	private function inspectParameter(Parameter $parameter, ValidationNode $node): void
	{
		$this->checkParameterExamples($parameter, $node);

		$contentNode = $node->getSubNode('content');
		foreach ($parameter->getContent() as $key => $mediaType) {
			$this->inspectMediaType($mediaType, $contentNode->getSubNode($key));
		}
	}

	private function checkParameterExamples(Parameter $parameter, ValidationNode $node): void
	{
		if ($parameter->hasExample() && $parameter->getExamples() !== []) {
			$node->addWarning("Both 'example' and 'examples' are defined, only one is allowed.");
		}
	}

	private function inspectResponses(Responses $responses, ValidationNode $node): void
	{
		foreach ($responses->getResponses() as $key => $response) {
			if ($response instanceof Reference) {
				continue;
			}

			$this->inspectResponse($response, $node->getSubNode($key));
		}
	}

	private function inspectResponse(Response $response, ValidationNode $node): void
	{
		$contentNode = $node->getSubNode('content');
		foreach ($response->getContent() as $key => $mediaType) {
			$this->inspectMediaType($mediaType, $contentNode->getSubNode($key));
		}

		$headersNode = $node->getSubNode('headers');
		foreach ($response->getHeaders() as $key => $header) {
			if ($header instanceof Reference) {
				continue;
			}

			$this->inspectHeader($header, $headersNode->getSubNode($key));
		}
	}

	private function inspectRequestBody(RequestBody $requestBody, ValidationNode $node): void
	{
		$contentNode = $node->getSubNode('content');
		foreach ($requestBody->getContent() as $key => $mediaType) {
			$this->inspectMediaType($mediaType, $contentNode->getSubNode($key));
		}
	}

}
