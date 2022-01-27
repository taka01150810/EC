<?php

namespace App\Constants;

Class Common
{
    const PRODUCT_ADD = '1';
    const PRODUCT_REDUCE = '2';
    const PRODUCT_LIST = [
        'add' => self::PRODUCT_ADD,//classの中で constを選択するときは self を使う
        'reduce' => self::PRODUCT_REDUCE
    ]; 
}