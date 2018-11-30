<?php

/**
 * This file is part of exotelis/coupon
 *
 * Copyright (c) 2018-2018 Sebastian Krah
 *
 * @package   exotelis/coupon
 * @author    Sebastian Krah <exotelis@mailbox.org>
 * @copyright 2018-2018 Sebastian Krah
 * @license   https://github.com/Exotelis/coupon/blob/master/LICENSE LGPL-3.0
 */

declare(strict_types=1);

// Backend Module
$GLOBALS['BE_MOD']['content']['exotelis_coupon'] = array
(
    'tables' => array('tl_exoteliscoupon')
);

// Frontend Module
$GLOBALS['FE_MOD']['application']['exoteliscoupon'] = 'Exotelis\ModuleExotelisCoupon';
