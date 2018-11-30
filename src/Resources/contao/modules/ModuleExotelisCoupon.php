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

use Contao;
use Patchwork\Utf8;

/**
 * Front end module of exotelis coupon
 *
 * @author Sebastian Krah <https://github.com/Exotelis>
 */
class ModuleExotelisCoupon extends Contao\Module
{
    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'mod_exotelis_coupons';

    /**
     * Do not display the module if there are no menu items
     *
     * @return string
     */
    public function generate()
    {
        if (TL_MODE == 'BE')
        {
            /** @var Contao\BackendTemplate|object $objTemplate */
            $objTemplate = new Contao\BackendTemplate('be_wildcard');
            $objTemplate->wildcard = '### ' . \utf8_strtoupper($GLOBALS['TL_LANG']['FMD']['exoteliscoupon'][0]) . ' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao?do=themes&table=tl_module&act=edit&id=' . $this->id;

            return $objTemplate->parse();
        }

        return parent::generate();
    }

    /**
     * Generate the module
     */
    protected function compile()
    {
        $coupon = CouponLayout::getInstance();

        $this->Template->couponLabel = $GLOBALS['TL_LANG']['MSC']['coupon_label'];
        $this->Template->couponButton = $GLOBALS['TL_LANG']['MSC']['coupon_button'];
        $this->Template->mask = $coupon->getMask();

        if(isset($_GET['coupon'])) {
            $strCouponcode =  \strtoupper(\trim(Contao\Input::get('coupon')));
        }

        $this->Template->action = \ampersand(Contao\Environment::get('indexFreeRequest'));

        // Redirect page
        if ($this->jumpTo && ($objTarget = $this->objModel->getRelated('jumpTo')) instanceof Contao\PageModel)
        {
            /** @var PageModel $objTarget */
            $this->Template->action = $objTarget->getFrontendUrl();
        }

        // Execute the coupon check
        if (isset($strCouponcode)) {
            $this->Template->couponCode = $strCouponcode;
            $this->Template->error = true;

            if ($strCouponcode === '') {
                $this->Template->errorTitle = $GLOBALS['TL_LANG']['ERR']['coupon_empty'][0];
                $this->Template->errorMessage = $GLOBALS['TL_LANG']['ERR']['coupon_empty'][1];

                return;
            }

            if (!preg_match($coupon->getPattern(), $strCouponcode))
            {
                $this->Template->errorTitle = $GLOBALS['TL_LANG']['ERR']['coupon_format'][0];
                $this->Template->errorMessage = \sprintf($GLOBALS['TL_LANG']['ERR']['coupon_format'][1], $coupon->getMask());

                return;
            }

            $objRow = $this->Database->prepare("SELECT * FROM tl_exoteliscoupon WHERE couponcode=?")->execute($strCouponcode);

            if ($objRow->numRows < 1) {
                $this->Template->errorTitle = $GLOBALS['TL_LANG']['ERR']['coupon_notFound'][0];
                $this->Template->errorMessage = $GLOBALS['TL_LANG']['ERR']['coupon_notFound'][1];

                return;
            }

            $result = $objRow->fetchAssoc();

            $this->Template->successTitle = $GLOBALS['TL_LANG']['MSC']['coupon_success'][0];
            $this->Template->valid = \sprintf($GLOBALS['TL_LANG']['MSC']['coupon_valid'],
                '<strong>' . $strCouponcode . '</strong>',
                '<strong>' . Contao\Date::parse(Contao\Config::get('dateFormat'), $result['stop']) . '</strong>'
            );
            $this->Template->value =  \sprintf($GLOBALS['TL_LANG']['MSC']['coupon_value'], '<strong>' . $result['value'] . '</strong>');
            $this->Template->description = $result['description'];

            $this->Template->error = false;
            $this->Template->success = true;
        }
    }
}