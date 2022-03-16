<?php
/**
 * @desc AuthProvider.php 描述信息
 * @author Tinywan(ShaoBo Wan)
 * @date 2022/3/16 15:39
 */

declare(strict_types=1);


namespace Tinywan\Nacos\Provider;


use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

class AuthProvider extends AbstractProvider
{
    /**
     * @desc: 方法描述
     * @param string $username
     * @param string $password
     * @return ResponseInterface
     * @throws GuzzleException
     * @author Tinywan(ShaoBo Wan)
     */
    public function login(string $username, string $password): ResponseInterface
    {
        return $this->client()->request('POST', 'nacos/v1/auth/users/login', [
            RequestOptions::QUERY => [
                'username' => $username,
            ],
            RequestOptions::FORM_PARAMS => [
                'password' => $password,
            ],
        ]);
    }
}