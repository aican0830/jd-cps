<?php

namespace JdCps;
use GuzzleHttp\Client as HttpClient;

class DefaultJdClient
{
    public $appKey;

    public $secretKey;

    public $gatewayUrl = "https://router.jd.com/api";

    public $format = "json";

    /** 是否打开入参check**/
    public $checkRequest = true;

    protected $signMethod = "md5";

    protected $apiVersion = "1.0";

    /**
     * Client constructor.
     * @param string $appKey
     * @param string $secretKey
     */
    public function __construct($appKey = "", $secretKey = "")
    {
        date_default_timezone_set("Asia/Shanghai");
        $this->appKey = $appKey;
        $this->secretKey = $secretKey;
    }

    /**
     * create sign
     * @param $params
     * @return string
     */
    protected function generateSign($params)
    {
        ksort($params);
        $stringToBeSigned = $this->secretKey;
        foreach ($params as $k => $v) {
            if (!is_array($v) && "@" != substr($v, 0, 1)) {
                $stringToBeSigned .= "$k$v";
            }
        }
        unset($k, $v);
        $stringToBeSigned .= $this->secretKey;

        return strtoupper(md5($stringToBeSigned));
    }

    /**
     * @param $request
     * @param string $response
     * @return mixed
     */
    public function getResponse($request, $response)
    {
        $result = json_decode($response, true);
        $key = str_replace('.', '_', $request->getMethod()) . '_response';
        if (!isset($result[$key]['result'])) {
            //TODO记录日志
            return [];
        }
        return json_decode($result[$key]['result'], true);
    }


    /**
     * @param $request
     * @return ResultSet|mixed
     */
    public function execute($request)
    {
        $params = [
            'app_key' => $this->appKey,
            'v' => $this->apiVersion,
            'format' => $this->format,
            'sign_method' => $this->signMethod,
            'method' => $request->getMethod(),
            'timestamp' => date('Y-m-d H:i:s'),
            'param_json' => $request->getParamsJsonString()
        ];

        $params["sign"] = $this->generateSign($params);

        $client = new HttpClient(['base_uri' => $this->gatewayUrl, 'query' => $params]);
        $result = $this->getResponse($request, $client->get('')->getBody()->getContents());
        return $result;
    }
}