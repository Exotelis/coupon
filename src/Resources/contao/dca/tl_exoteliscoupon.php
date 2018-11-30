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

$GLOBALS['TL_DCA']['tl_exoteliscoupon'] = array
(
    // Config
    'config' => array
    (
        'dataContainer'     => 'Table',
        'enableVersioning'  => true,
        'sql'               => array
        (
            'keys' => array
            (
                'id'            => 'primary',
                'couponcode'    => 'index'
            )
        )
    ),

    // List
    'list' => array
    (
        'sorting' => array
            (
                'mode'          => 1,
                'fields'        => array('stop'),
                'flag'          => 1,
                'panelLayout'   => 'filter;search,limit'
            ),
        'label' => array
        (
            'fields'            => array('couponcode', 'recipient', 'value', 'description', 'stop'),
            'showColumns'       => true
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'         => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'          => 'act=select',
                'class'         => 'header_edit_all',
                'attributes'    => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'         => &$GLOBALS['TL_LANG']['tl_exoteliscoupon']['edit'],
                'href'          => 'act=edit',
                'icon'          => 'edit.svg'
            ),
            'delete' => array
            (
                'label'         => &$GLOBALS['TL_LANG']['tl_exoteliscoupon']['delete'],
                'href'          => 'act=delete',
                'icon'          => 'delete.svg',
                'attributes'    => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'show' => array
            (
                'label'         => &$GLOBALS['TL_LANG']['tl_exoteliscoupon']['show'],
                'href'          => 'act=show',
                'icon'          => 'show.svg'
            )
        )
    ),

    // Palettes
    'palettes' => array
    (
        'default'  => '{coupon_information},couponcode,recipient,value,description,start,stop',
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'label'                 => &$GLOBALS['TL_LANG']['tl_exoteliscoupon']['id'],
            'sql'                   => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array
        (
            'sql'                   => "int(10) unsigned NOT NULL default '0'"
        ),
        'couponcode' => array
        (
            'label'                 => &$GLOBALS['TL_LANG']['tl_exoteliscoupon']['couponcode'],
            'exclude'               => true,
            'search'                => true,
            'sorting'               => false,
            'flag'                  => 1,
            'inputType'             => 'text',
            'eval'                  => array('mandatory'=>true, 'unique'=>true, 'maxlength'=>23, 'tl_class'=>'w100'),
            'sql'                   => "varchar(23) NOT NULL default ''",
            'load_callback'         => array
            (
                array('tl_exoteliscoupon', 'generateCouponCode'),
            )
        ),
        'recipient' => array
        (
            'label'                 => &$GLOBALS['TL_LANG']['tl_exoteliscoupon']['recipient'],
            'exclude'               => true,
            'search'                => true,
            'sorting'               => false,
            'flag'                  => 1,
            'inputType'             => 'text',
            'eval'                  => array('mandatory'=>true, 'maxlength'=>100, 'tl_class'=>'w100'),
            'sql'                   => "varchar(100) NOT NULL default ''"
        ),
        'value' => array
        (
            'label'                 => &$GLOBALS['TL_LANG']['tl_exoteliscoupon']['value'],
            'exclude'               => true,
            'search'                => true,
            'sorting'               => true,
            'flag'                  => 1,
            'inputType'             => 'text',
            'eval'                  => array('mandatory'=>true, 'rgxp'=>'digit', 'maxlength'=>32, 'tl_class'=>'w50'),
            'sql'                   => "varchar(32) NOT NULL default ''"
        ),
        'description' => array
        (
            'label'                 => &$GLOBALS['TL_LANG']['tl_exoteliscoupon']['description'],
            'exclude'               => true,
            'search'                => true,
            'sorting'               => false,
            'flag'                  => 1,
            'inputType'             => 'text',
            'eval'                  => array('mandatory'=>false, 'maxlength'=>255, 'tl_class'=>'w50'),
            'sql'                   => "varchar(255) NOT NULL default ''"
        ),
        'start' => array
        (
            'label'                 => &$GLOBALS['TL_LANG']['tl_exoteliscoupon']['start'],
            'exclude'               => true,
            'sorting'               => true,
            'flag'                  => 9,
            'inputType'             => 'text',
            'eval'                  => array('mandatory'=>true, 'rgxp'=>'date', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
            'sql'                   => "varchar(11) NOT NULL default ''"
        ),
        'stop' => array
        (
            'label'                 => &$GLOBALS['TL_LANG']['tl_exoteliscoupon']['stop'],
            'exclude'               => true,
            'sorting'               => true,
            'flag'                  => 9,
            'inputType'             => 'text',
            'eval'                  => array('mandatory'=>true, 'rgxp'=>'date', 'datepicker'=>true, 'tl_class'=>'w50 wizard'),
            'sql'                   => "varchar(11) NOT NULL default ''"
        )
    )
);

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Sebastian Krah <https://github.com/Exotelis>
 */
class tl_exoteliscoupon extends Contao\Backend
{
    /**
     * Generates a Coupon Code
     *
     * @param string $varValue      The value of the field
     * @param DataContainer $dc     The DataContainer object
     *
     * @throws Exception
     *
     * @return string               The generated Code
     */
    public function generateCouponCode($varValue, DataContainer $dc)
    {
        if(empty($varValue)) {
            $coupon = \Exotelis\CouponLayout::getInstance();
            $chars = $coupon->getAllowedCharacters();
            $mask = $coupon->getMask();

            // Generate Code
            $varValue = '';

            for ($i = 0; $i < strlen($mask); $i++) {
                if ($mask[$i] === 'X') {
                    $varValue .= $chars[mt_rand(0, strlen($chars)-1)];
                } else {
                    $varValue .= $mask[$i];
                }
            }
        }

        return $varValue;
    }
}