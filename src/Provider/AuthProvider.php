<?php
/**
 * @desc AuthProvider.php 描述信息
 * @author Tinywan(ShaoBo Wan)
 * @date 2022/3/16 15:39
 */

declare(strict_types=1);


namespace Tinywan\Nacos\Provider;


use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Tinywan\Nacos\Exception\NacosAuthException;

class AuthProvider extends AbstractProvider
{
    /**
     * @desc: 授权登录
     * @param string $username
     * @param string $password
     * @return ResponseInterface
     * @throws GuzzleException
     * @author Tinywan(ShaoBo Wan)
     */
    public function login(string $username, string $password): ResponseInterface
    {
        try {
            $response = $this->client()->request('POST', 'nacos/v1/auth/users/login', [
                RequestOptions::QUERY => [
                    'username' => $username,
                ],
                RequestOptions::FORM_PARAMS => [
                    'password' => $password,
                ],
            ]);
        }catch (RequestException $exception) {
            if (403 === $exception->getCode()) {
                throw new NacosAuthException('Nacos服务端鉴权失败，'.$exception->getResponse()->getBody()->getContents());
            }
            throw new NacosAuthException($exception->getMessage(),$exception->getCode());
        }
        return $response;
    }
}