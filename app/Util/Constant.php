<?php

namespace App\Util;

abstract class Constant
{
    const CUSTOMER_TYPE = array('vi' => "Tiếng Việt", 'en' => "English", 'cn' => 'Chinese');

    const VOTE_STATUS__INVALID = 0;
    public const VOTE_STATUS__VALID = 1;
    public const CONTACT_STATUS__SUBCESS = 1;
    public const CONTACT_TYPE__SUBCRIBE = 0;
    public const CONTACT_TYPE__MEMBERSHIP = 1;
    const PROGRAM_PRODUCT_STATUS__ACTIVE = 1;

    public const PARENT_ID_ROOT = 0;
    public const LANGUAGE_DEFAULT = 'vi';
}
