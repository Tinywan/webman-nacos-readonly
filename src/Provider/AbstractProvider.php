<?php
/**
 * @desc AbstractProvider.php 描述信息
 * @author Tinywan(ShaoBo Wan)
 * @date 2022/3/16 15:26
 */

declare(strict_types=1);


namespace Tinywan\Nacos\Provider;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Tinywan\Nacos\Nacos;
use Tinywan\Nacos\Traits\Authentication;
use Tinywan\Nacos\Traits\ErrorMsg;

abstract class AbstractProvider
{
    use Authentication, ErrorMsg;

    /**
     * @var string
     */
    protected string $host = '127.0.0.1';

    /**
     * @var int
     */
    protected int $port = 8848;

    /**
     * @var string|null
     */
    protected ?string $username = null;

    /**
     * @var string|null
     */
    protected ?string $password = null;

    /**
     * AbstractProvider constructor.
     * @param Nacos $nacos
     */
    public function __construct(Nacos $nacos)
    {
        $config = config('plugin.tinywan.nacos.app.nacos');
        isset($config['host']) && $this->host = (string) $config['host'];
        isset($config['port']) && $this->port = (int) $config['port'];
        isset($config['username']) && $this->username = (string) $config['username'];
        isset($config['password']) && $this->password = (string) $config['password'];
    }

    /**
     * @desc: 方法描述
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return bool|string
     * @throws GuzzleException
     * @author Tinywan(ShaoBo Wan)
     */
    public function request(string $method, string $uri, array $options = [])
    {
        try {
            $token = $this->issueToken();
            $token && $options[RequestOptions::QUERY]['accessToken'] = $token;
            $response = $this->client()->request($method, $uri, $options);
        }catch (RequestException $exception){
            if ($exception->hasResponse()) {
                if (200 != $exception->getResponse()->getStatusCode()) {
                    $jsonStr = $exception->getResponse()->getBody()->getContents();
                    $content = json_decode($jsonStr, true);
                    return $this->setError(false, '温馨提示：' . $content['msg'] ?? '未知的错误信息');
                }
            }
            return $this->setError(false, '服务端提示：' . $exception->getMessage());
        }
        return $response->getBody()->getContents();
    }

    /**
     * @desc: 方法描述
     * @return Client
     * @author Tinywan(ShaoBo Wan)
     */
    public function client(): Client
    {
        $config = [
            'base_uri' => sprintf('http://%s:%d', $this->host ?? '127.0.0.1', $this->port ?? 8848),
        ];
        return new Client($config);
    }

    /**
     * @desc: 方法描述
     * @param ResponseInterface $response
     * @return array
     * @author Tinywan(ShaoBo Wan)
     */
    protected function handleResponse(ResponseInterface $response): array
    {
        $statusCode = $response->getStatusCode();
        $contents = (string) $response->getBody();
        if ($statusCode !== 200) {
            throw new RequestException($contents, $statusCode);
        }
        try {
            $decode = json_decode($contents, true, 512, 0 | JSON_THROW_ON_ERROR);
        } catch (\Throwable $exception) {
            throw new \InvalidArgumentException($exception->getMessage(), $exception->getCode());
        }
        return $decode;
    }

    /**
     * @desc: 方法描述
     * @param array $input
     * @return array
     * @author Tinywan(ShaoBo Wan)
     */
    protected function filter(array $input): array
    {
        $result = [];
        foreach ($input as $key => $value) {
            if ($value !== null) {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}