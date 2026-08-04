<?php

namespace Pietroagazzi\Mechanic\Tests\Http;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Pietroagazzi\Mechanic\Http\JsonResponse;
use Pietroagazzi\Mechanic\Http\Response;

#[CoversClass(JsonResponse::class)]
#[UsesClass(Response::class)]
class JsonResponseTest extends TestCase
{
	public function testJsonResponse(): void
	{
		$response = new JsonResponse([
			'users' => [
				[
					'name' => 'Pietro',
					'age' => 20,
				],
				[
					'name' => 'John',
					'age' => 30,
				],
			],
		]);

		$this->assertEquals(
			'{"users":[{"name":"Pietro","age":20},{"name":"John","age":30}]}',
			$response->getContent()
		);
	}
}
