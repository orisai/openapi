<?php declare(strict_types = 1);

namespace Tests\Orisai\OpenAPI\Unit\Spec;

use Orisai\OpenAPI\Enum\ParameterIn;
use Orisai\OpenAPI\Spec\Operation;
use Orisai\OpenAPI\Spec\Parameter;
use Orisai\OpenAPI\Spec\PathItem;
use Orisai\OpenAPI\Spec\Reference;
use Orisai\OpenAPI\Spec\Server;
use PHPUnit\Framework\TestCase;

final class PathItemTest extends TestCase
{

	public function test(): void
	{
		$pi1 = new PathItem();
		self::assertSame([], $pi1->toArray());

		$pi2 = new PathItem();
		$pi2->ref = 'ref';
		$pi2->summary = 'summary';
		$pi2->description = 'description';

		$pi2->get = $get = new Operation();
		$get->summary = 'get';

		$pi2->put = $put = new Operation();
		$put->summary = 'put';

		$pi2->post = $post = new Operation();
		$post->summary = 'post';

		$pi2->delete = $delete = new Operation();
		$delete->summary = 'delete';

		$pi2->options = $options = new Operation();
		$options->summary = 'options';

		$pi2->head = $head = new Operation();
		$head->summary = 'head';

		$pi2->patch = $patch = new Operation();
		$patch->summary = 'patch';

		$pi2->trace = $trace = new Operation();
		$trace->summary = 'trace';

		$pi2->servers[] = $pi2s1 = new Server('https://example.com');
		$pi2->servers[] = $pi2s2 = new Server('https://example2.com');

		$pi2->parameters[] = $pi2p1 = new Parameter('p1', ParameterIn::path());
		$pi2->parameters[] = $pi2p2 = new Reference('p2');

		$pi2->addExtension('x-a', null);

		self::assertSame(
			[
				'$ref' => 'ref',
				'summary' => 'summary',
				'description' => 'description',
				'get' => $get->toArray(),
				'put' => $put->toArray(),
				'post' => $post->toArray(),
				'delete' => $delete->toArray(),
				'options' => $options->toArray(),
				'head' => $head->toArray(),
				'patch' => $patch->toArray(),
				'trace' => $trace->toArray(),
				'servers' => [
					$pi2s1->toArray(),
					$pi2s2->toArray(),
				],
				'parameters' => [
					$pi2p1->toArray(),
					$pi2p2->toArray(),
				],
				'x-a' => null,
			],
			$pi2->toArray(),
		);
	}

}
