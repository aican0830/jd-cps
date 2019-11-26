<?php

use PHPUnit\Framework\TestCase;

final class DefaultJdClientTest extends TestCase
{
    public function testExecute(): void
    {
        $appKey = "";
        $appSecret = "";
        $client = new \JdCps\DefaultJdClient($appKey, $appSecret);

        $params  = [
            'goodsReq' =>  [
                'eliteId' => 1
            ]
        ];
        $request = new \JdCps\Request('jd.union.open.goods.jingfen.query', $params);
        $result = $client->execute($request);

        var_dump($result);
    }

}
