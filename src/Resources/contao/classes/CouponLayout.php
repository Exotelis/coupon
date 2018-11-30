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

namespace Exotelis;

/**
 * Class that defines the layout of the coupon codes
 *
 * Mask:
 * The - is always a seperator
 * The X is an arbitrary char
 *
 * Charset:
 * Allowed characters
 *
 * Pattern:
 * The Pattern must match the mask for validation
 *
 * @author Sebastian Krah <https://github.com/Exotelis>
 */
class CouponLayout
{
    /**
     * Static instance of the class
     *
     * @var \Exotelis\CouponLayout
     */
    protected static $_instance = null;

     /**
     * Mask
     * @var string
     */
    protected $mask;

    /**
     * AllowedCharacters
     * @var string
     */
    protected $allowedCharacters;

    /**
     * Pattern
     * @var string
     */
    protected $pattern;

    /**
     * If instance don't exist, create it
     * otherwise return it
     *
     * @return  \Exotelis\CouponLayout
     */
    public static function getInstance()
    {
        if (null === self::$_instance)
        {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    /**
     * CouponLayout constructor.
     */
    protected function __construct()
    {
        $this->mask = 'XXXXX-XXXXX-XXXXX-XXXXX';
        $this->allowedCharacters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $this->pattern = '/^([A-Z0-9]{5}-){3}[A-Z0-9]{5}$/';
    }

    /**
     * CouponLayout clone
     */
    protected function __clone() {}

    /**
     * Return the coupon mask
     * @return string
     */
    public function getMask()
    {
        return $this->mask;
    }

    /**
     * Return the allowed characters
     * @return string
     */
    public function getAllowedCharacters()
    {
        return $this->allowedCharacters;
    }

    /**
     * Returns the coupon pattern
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }
}