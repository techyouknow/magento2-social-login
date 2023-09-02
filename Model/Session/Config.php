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

namespace Techyouknow\SocialLogin\Model\Session;

use Magento\Framework\Session\Config as MagentoConfig;

class Config extends MagentoConfig
{
    /**
     * Set an individual option with 'samesite' preference
     *
     * @param string $option
     * @param mixed $value
     * @return $this
     */
    public function setOption($option, $value)
    {
        if($this->isSocialNetworkEnable() && $this->isSecureAndSameSiteCookiesEnabled()) {
            switch ($option) {
                case 'session.cookie_secure':
                    $value = 1;
                    break;
                case 'session.cookie_samesite':
                    $value = 'None';
                    break;
            }
        }

        return parent::setOption($option, $value);
    }

    public function isSecureAndSameSiteCookiesEnabled()
    {
        return $this->_scopeConfig->getValue('techyouknow_social_network/adapters/apple/change_session', $this->_scopeType);
    }

    public function isSocialNetworkEnable() {
        return $this->_scopeConfig->getValue('techyouknow_social_network/general/enable', $this->_scopeType);
    }
}