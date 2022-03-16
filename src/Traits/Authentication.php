<?php
/**
 * @desc 认证 Authentication
 * @help https://nacos.io/zh-cn/docs/auth.html
 * @author Tinywan(ShaoBo Wan)
 * @date 2022/3/16 15:29
 */

declare(strict_types=1);


namespace Tinywan\Nacos\Traits;


trait Authentication
{
    /**
     * @var string|null
     */
    private ?string $accessToken = null;

    /**
     * @var int
     */
    private int $expireTime = 0;

    /**
     * @desc: 获取访问令牌
     * @return string|null
     * @author Tinywan(ShaoBo Wan)
     */
    public function issueToken(): ?string
    {
        if ($this->username === null || $this->password === null) {
            return null;
        }

        if (!$this->isExpired()) {
            return $this->accessToken;
        }

        $result = $this->handleResponse(
            $this->nacos->auth->login($this->username, $this->password)
        );

        $this->accessToken = $result['accessToken'];
        $this->expireTime = $result['tokenTtl'] + time();

        return $this->accessToken;
    }

    /**
     * @desc: 方法描述
     * @return bool
     * @author Tinywan(ShaoBo Wan)
     */
    protected function isExpired(): bool
    {
        if (isset($this->accessToken) && $this->expireTime > time() + 60) {
            return false;
        }
        return true;
    }

}