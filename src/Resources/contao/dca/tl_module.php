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

// Add palettes to tl_module
$GLOBALS['TL_DCA']['tl_module']['palettes']['exoteliscoupon'] = '{title_legend},name,headline,type;{redirect_legend:hide},jumpTo;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';

// Add fields to tl_module
$GLOBALS['TL_DCA']['tl_module']['fields']['customTpl'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['customTpl'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options_callback'        => array('tl_module_exoteliscoupon', 'getModuleTemplates'),
    'eval'                    => array('includeBlankOption'=>true, 'chosen'=>true, 'tl_class'=>'w50'),
    'sql'                     => "varchar(64) NOT NULL default ''"
);


/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Sebastian Krah <https://github.com/Exotelis>
 */
class tl_module_exoteliscoupon extends Contao\Backend
{
    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    /**
     * Return all news templates as array
     *
     * @return array
     */
    public function getModuleTemplates()
    {
        return $this->getTemplateGroup('mod_exotelis_coupons');
    }

}