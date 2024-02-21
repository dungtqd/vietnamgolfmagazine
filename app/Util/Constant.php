<?php

namespace App\Util;

abstract class Constant
{
    const CUSTOMER_TYPE = array('vi' => "Tiếng Việt", 'en' => "English", 'cn' => 'Chinese');

    const VOTE_STATUS__INVALID = 0;
    public const VOTE_STATUS__VALID = 1;
    const PROGRAM_PRODUCT_STATUS__ACTIVE = 1;

    public const PARENT_ID_ROOT = 0;
    public const LANGUAGE_DEFAULT = 'vi';
}
