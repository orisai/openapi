<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Validation;

use Generator;
use Orisai\ObjectMapper\Context\FieldContext;
use Orisai\ObjectMapper\Exception\InvalidData;
use Orisai\ObjectMapper\Printers\ErrorVisualPrinter;
use Orisai\ObjectMapper\Printers\TypeToStringConverter;
use Orisai\ObjectMapper\Processing\Processor;
use Orisai\ObjectMapper\Rules\RuleManager;
use Orisai\ObjectMapper\Tester\ObjectMapperTester;
use Orisai\OpenAPI\Spec\OpenAPI;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Yaml\Yaml;
use function assert;
use function file_get_contents;
use function json_decode;
use function strpos;
use function strtolower;
use function substr;
use const JSON_THROW_ON_ERROR;

final class SchemasValidationTest extends TestCase
{

	private static Processor $processor;

	private static RuleManager $ruleManager;

	private static FieldContext $context;

	public static function setUpBeforeClass(): void
	{
		parent::setUpBeforeClass();

		$tester = new ObjectMapperTester();
		$deps = $tester->buildDependencies();
		self::$context = $deps->createFieldContext();
		self::$ruleManager = $deps->ruleManager;
		self::$processor = $deps->processor;
	}

	/**
	 * @dataProvider provide
	 */
	public function test(string $fileName): void
	{
		$fileContent = file_get_contents($fileName);
		$data = strtolower(substr($fileName, -5, 5)) === '.json'
			? json_decode($fileContent, true, 512, JSON_THROW_ON_ERROR)
			: Yaml::parse($fileContent);

		//$rule = self::$ruleManager->getRule(MappedObjectRule::class);
		//$args = new MappedObjectArgs(OpenAPI::class);
		//$type = $rule->createType($args, self::$context);
		//self::assertNull($type);

		$openapi = null;
		try {
			$openapi = self::$processor->process($data, OpenAPI::class);
		} catch (InvalidData $data) {
			$printer = new ErrorVisualPrinter(new TypeToStringConverter());
			self::assertSame('', $printer->printError($data));
		}

		self::assertInstanceOf(OpenAPI::class, $openapi);
	}

	public function provide(): Generator
	{
		// examples from https://github.com/OAI/OpenAPI-Specification/tree/master/examples/v3.0
		$oaiExamples = [
			// TODO symfony/yaml can not read this file!?
			// __DIR__ . '/../../../vendor/oai/openapi-specification/examples/v3.0/api-with-examples.yaml',
			//__DIR__ . '/../../../vendor/oai/openapi-specification/examples/v3.0/callback-example.yaml',
			//__DIR__ . '/../../../vendor/oai/openapi-specification/examples/v3.0/link-example.yaml',
			//__DIR__ . '/../../../vendor/oai/openapi-specification/examples/v3.0/petstore.yaml',
			//__DIR__ . '/../../../vendor/oai/openapi-specification/examples/v3.0/petstore-expanded.yaml',
			//__DIR__ . '/../../../vendor/oai/openapi-specification/examples/v3.0/uspto.yaml',
			__DIR__ . '/../../../vendor/oai/openapi-specification-3.1/examples/v3.1/webhook-example.yaml',
		];

		foreach ($oaiExamples as $example) {
			yield [$example];
		}

		// examples from https://github.com/Mermade/openapi3-examples
		$mermadeExamples = [
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/pass/externalPathItemRef.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/pass/deprecated.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/pass/swagger2openapi/openapi.json',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/pass/gluecon/example1_from_._Different_parameters.md.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/pass/gluecon/example1_from_._Fixed_file.md.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/pass/gluecon/example1_from_._Different_parameters.md.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/pass/gluecon/example1_from_._Fixed_file.md.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/pass/gluecon/example1_from_._Fixed_multipart.md.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/pass/gluecon/example1_from_._Improved_examples.md.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/pass/gluecon/example1_from_._Improved_pathdescriptions.md.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/pass/gluecon/example1_from_._Improved_securityschemes.md.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/pass/gluecon/example1_from_._Improved_serverseverywhere.md.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/pass/gluecon/example1_from_._New_callbacks.md.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/pass/gluecon/example1_from_._New_links.md.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/pass/gluecon/example2_from_._Different_parameters.md.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/pass/gluecon/example2_from_._Different_requestbody.md.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/pass/gluecon/example2_from_._Different_servers.md.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/pass/gluecon/example2_from_._Fixed_multipart.md.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/pass/gluecon/example2_from_._Improved_securityschemes.md.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/pass/gluecon/example2_from_._New_callbacks.md.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/pass/gluecon/example2_from_._New_links.md.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/pass/gluecon/example3_from_._Different_parameters.md.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/pass/gluecon/example3_from_._Different_servers.md.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/pass/gluecon/example4_from_._Different_parameters.md.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/pass/gluecon/example5_from_._Different_parameters.md.yaml',
			// TODO symfony/yaml can not read this file!?
			// __DIR__ . '/../../../vendor/mermade/openapi3-examples/pass/OAI/api-with-examples.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/pass/OAI/petstore-expanded.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/pass/OAI/petstore.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/pass/OAI/uber.yaml',

			__DIR__ . '/../../../vendor/mermade/openapi3-examples/malicious/rapid7-html.json',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/malicious/rapid7-java.json',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/malicious/rapid7-js.json',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/malicious/rapid7-php.json',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/malicious/rapid7-ruby.json',
			// __DIR__ . '/../../../vendor/mermade/openapi3-examples/malicious/yamlbomb.yaml',
		];
		$mermadeExamples = [];

		foreach ($mermadeExamples as $example) {
			yield [$example];
		}

		// examples from https://github.com/APIs-guru/openapi-directory/tree/openapi3.0.0/APIs
		///** @var RecursiveDirectoryIterator|RecursiveIteratorIterator $it */
		//$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__ . '/../../../vendor/apis-guru/openapi-directory/APIs'));
		//$it->rewind();
		//while ($it->valid()) {
		//	if ($it->getBasename() === 'openapi.yaml') {
		//		yield [$it->key()];
		//	}
		//	$it->next();
		//}

		$it = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator(__DIR__ . '/../../../vendor/nexmo/api-specification/definitions'),
		);
		assert($it instanceof RecursiveDirectoryIterator || $it instanceof RecursiveIteratorIterator);
		$it->rewind();
		while ($it->valid()) {
			if ($it->getExtension() === 'yml'
				&& strpos($it->getSubPath(), 'common') === false
				&& $it->getBasename() !== 'voice.v2.yml' // contains invalid references
			) {
				//yield [$it->key()];
			}

			$it->next();
		}
	}

}
