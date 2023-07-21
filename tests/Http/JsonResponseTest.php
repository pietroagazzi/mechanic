<?php

namespace Http;

use PHPUnit\Framework\TestCase;
use Pietroagazzi\Mechanic\Http\JsonResponse;

class JsonResponseTest extends TestCase
{
	/**
	 * @covers \Pietroagazzi\Mechanic\Http\JsonResponse::__construct
	 * @covers \Pietroagazzi\Mechanic\Http\JsonResponse::encode
	 * @uses   \Pietroagazzi\Mechanic\Http\Response
	 */
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
