<?php

if (!function_exists('aliyun_mns')) {
    /**
     * @return \Zqhong\FastdAliyunMNS\Client
     */
    function aliyun_mns()
    {
        return app()->get('aliyun_mns');
    }
}
