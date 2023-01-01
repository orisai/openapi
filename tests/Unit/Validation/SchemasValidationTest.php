<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Validation;

use Generator;
use Orisai\ObjectMapper\Exception\InvalidData;
use Orisai\ObjectMapper\Printers\ErrorVisualPrinter;
use Orisai\ObjectMapper\Printers\TypeToStringConverter;
use Orisai\ObjectMapper\Processing\Processor;
use Orisai\ObjectMapper\Tester\ObjectMapperTester;
use Orisai\OpenAPI\Spec\OpenAPI;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;
use function file_get_contents;
use function json_decode;
use function str_replace;
use function strtolower;
use function substr;
use const JSON_THROW_ON_ERROR;

final class SchemasValidationTest extends TestCase
{

	private static Processor $processor;

	public static function setUpBeforeClass(): void
	{
		parent::setUpBeforeClass();

		$tester = new ObjectMapperTester();
		$deps = $tester->buildDependencies();
		self::$processor = $deps->processor;
	}

	/**
	 * @dataProvider provide
	 */
	public function test(string $fileName): void
	{
		// TODO - tyhle todos + todos v kódu

		$fileContent = file_get_contents($fileName);
		// TODO - vypsat co se skipnulo, protože není validní soubor
		$data = strtolower(substr($fileName, -5, 5)) === '.json'
			? json_decode($fileContent, true, 512, JSON_THROW_ON_ERROR)
			: Yaml::parse($fileContent);

		// TODO - vypsat co se skipnulo, protože chybí verze openapi
		// TODO - vypsat co se skipnulo, protože není podporovaná verze openapi
		$openapi = null;
		try {
			$openapi = self::$processor->process($data, OpenAPI::class);
		} catch (InvalidData $data) {
			$printer = new ErrorVisualPrinter(new TypeToStringConverter());
			// TODO - vypsat chyby při validaci
			self::assertSame('', $printer->printError($data));
		}

		self::assertInstanceOf(OpenAPI::class, $openapi);
		// TODO - zvalidovat chyby napříč schematem
		// TODO - vypsat chyby napříč schematem

		// TODO - vypisovat pro vše výše zmíněné, co se změnilo?
		//		- souhrn, v jakém bodě nastal problém nebo jaký problém zmizel
	}

	public function provide(): Generator
	{
		$basePath = __DIR__ . '/../../../vendor/';

		foreach ($this->provideSource() as $item) {
			$path = str_replace($basePath, '', $item[0]);

			yield $path => $item;
		}
	}

	private function provideSource(): Generator
	{
		// TODO - snapshot testing - compare tests with diffs
		//		- when updating schema repositories
		//		- when anything in code changes
		//		- when object mapper changes
		//		- when schema has external deps that need to be downloaded
		//		- https://github.com/spatie/phpunit-snapshot-assertions

		// examples from https://github.com/OAI/OpenAPI-Specification/tree/36a3a67264cc1c4f1eff110cea3ebfe679435108/examples
		// check for changes with https://github.com/OAI/OpenAPI-Specification/compare/36a3a67264cc1c4f1eff110cea3ebfe679435108...HEAD
		$oaiExamples = [
			//__DIR__ . '/../../../vendor/oai/openapi-specification/examples/v3.0/api-with-examples.yaml',
			//__DIR__ . '/../../../vendor/oai/openapi-specification/examples/v3.0/callback-example.yaml',
			//__DIR__ . '/../../../vendor/oai/openapi-specification/examples/v3.0/link-example.yaml',
			//__DIR__ . '/../../../vendor/oai/openapi-specification/examples/v3.0/petstore.yaml',
			//__DIR__ . '/../../../vendor/oai/openapi-specification/examples/v3.0/petstore-expanded.yaml',
			//__DIR__ . '/../../../vendor/oai/openapi-specification/examples/v3.0/uspto.yaml',
			__DIR__ . '/../../../vendor/oai/openapi-specification/examples/v3.1/non-oauth-scopes.yaml',
			//__DIR__ . '/../../../vendor/oai/openapi-specification/examples/v3.1/webhook-example.yaml',
		];

		foreach ($oaiExamples as $example) {
			yield [$example];
		}

		// examples from https://github.com/Mermade/openapi3-examples/tree/9c2997e1a25919a8182080cc43a4db06d2dc775d
		// check for changes with https://github.com/Mermade/openapi3-examples/compare/9c2997e1a25919a8182080cc43a4db06d2dc775d...HEAD
		$mermadeExamples = [
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/pass/externalPathItemRef.yaml',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/pass/deprecated.yaml',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/pass/swagger2openapi/openapi.json',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/pass/gluecon/example1_from_._Different_parameters.md.yaml',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/pass/gluecon/example1_from_._Fixed_file.md.yaml',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/pass/gluecon/example1_from_._Different_parameters.md.yaml',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/pass/gluecon/example1_from_._Fixed_file.md.yaml',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/pass/gluecon/example1_from_._Fixed_multipart.md.yaml',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/pass/gluecon/example1_from_._Improved_examples.md.yaml',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/pass/gluecon/example1_from_._Improved_pathdescriptions.md.yaml',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/pass/gluecon/example1_from_._Improved_securityschemes.md.yaml',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/pass/gluecon/example1_from_._Improved_serverseverywhere.md.yaml',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/pass/gluecon/example1_from_._New_callbacks.md.yaml',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/pass/gluecon/example1_from_._New_links.md.yaml',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/pass/gluecon/example2_from_._Different_parameters.md.yaml',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/pass/gluecon/example2_from_._Different_requestbody.md.yaml',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/pass/gluecon/example2_from_._Different_servers.md.yaml',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/pass/gluecon/example2_from_._Fixed_multipart.md.yaml',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/pass/gluecon/example2_from_._Improved_securityschemes.md.yaml',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/pass/gluecon/example2_from_._New_callbacks.md.yaml',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/pass/gluecon/example2_from_._New_links.md.yaml',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/pass/gluecon/example3_from_._Different_parameters.md.yaml',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/pass/gluecon/example3_from_._Different_servers.md.yaml',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/pass/gluecon/example4_from_._Different_parameters.md.yaml',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/pass/gluecon/example5_from_._Different_parameters.md.yaml',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/pass/OAI/api-with-examples.yaml',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/pass/OAI/petstore-expanded.yaml',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/pass/OAI/petstore.yaml',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/pass/OAI/uber.yaml',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/malicious/rapid7-html.json',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/malicious/rapid7-java.json',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/malicious/rapid7-js.json',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/malicious/rapid7-php.json',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/malicious/rapid7-ruby.json',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.0/malicious/yamlbomb.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.1/pass/comp_pathitems.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.1/pass/info_summary.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.1/pass/license_identifier.yaml',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.1/pass/mega.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.1/pass/minimal_comp.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.1/pass/minimal_hooks.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.1/pass/minimal_paths.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.1/pass/path_no_response.yaml',
			__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.1/pass/path_var_empty_pathitem.yaml',
			//__DIR__ . '/../../../vendor/mermade/openapi3-examples/3.1/pass/schema.yaml',
		];

		foreach ($mermadeExamples as $example) {
			yield [$example];
		}

		// examples from https://github.com/APIs-guru/openapi-directory/tree/20999cad0ba2d05084403a1f15e9f1823ee831e5/APIs
		// check for changes with https://github.com/APIs-guru/openapi-directory/compare/20999cad0ba2d05084403a1f15e9f1823ee831e5...HEAD
		///** @var RecursiveDirectoryIterator|RecursiveIteratorIterator $it */
		//$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__ . '/../../../vendor/apis-guru/openapi-directory/APIs'));
		//$it->rewind();
		//while ($it->valid()) {
		//	if ($it->getBasename() === 'openapi.yaml') {
		//		yield [$it->key()];
		//	}
		//	$it->next();
		//}
	}

}
