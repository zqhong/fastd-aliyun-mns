<?php

if (!function_exists('aliyun_mns')) {
    /**
     * @return \AliyunMNS\Client
     */
    function aliyun_mns()
    {
        return app()->get('aliyun_mns');
    }
}
