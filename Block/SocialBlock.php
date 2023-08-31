<?php
/*
 * MIT License
 *
 * Copyright (c) 2023 Techyouknow
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Techyouknow\SocialLogin\Block;

use Magento\Framework\View\Element\Template;
use Techyouknow\SocialLogin\Helper\SocialHelper;

class SocialBlock extends Template
{
    private $socialHelper;
    private $isSocialLoginActive;
    private $activeSocialNetworks;

    public function __construct(
        Template\Context $context,
        SocialHelper $socialHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->socialHelper = $socialHelper;
        $this->isSocialLoginActive = $this->socialHelper->isSocialNetworkEnable();
        $this->activeSocialNetworks = $this->socialHelper->getActiveSocialNetworksList();
    }

    public function isSocialLoginActive()
    {
        return $this->isSocialLoginActive;
    }

    public function getActiveSocialNetworks()
    {
        return $this->activeSocialNetworks;
    }
}
