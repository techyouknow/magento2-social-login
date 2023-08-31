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

namespace Techyouknow\SocialLogin\Model;


class SocialLoginCustomer extends \Magento\Framework\Model\AbstractModel implements \Techyouknow\SocialLogin\Api\Data\SocialNetworkCustomer
{

    protected function _construct() {
        $this->_init(\Techyouknow\SocialLogin\Model\ResourceModel\SocialLoginCustomer::class);
    }

    public function getSocialId()
    {
        return $this->getData(\Techyouknow\SocialLogin\Api\Data\SocialNetworkCustomer::SOCIAL_ID);
    }

    public function setSocialId($socialId)
    {
        $this->setData(\Techyouknow\SocialLogin\Api\Data\SocialNetworkCustomer::SOCIAL_ID, $socialId);

        return $this;
    }

    public function getCustomerId()
    {
        return $this->getData(\Techyouknow\SocialLogin\Api\Data\SocialNetworkCustomer::CUSTOMER_ID);
    }

    public function setCustomerId($customerId)
    {
        $this->setData(\Techyouknow\SocialLogin\Api\Data\SocialNetworkCustomer::CUSTOMER_ID, $customerId);

        return $this;
    }

    public function getSocialType()
    {
        return $this->getData(\Techyouknow\SocialLogin\Api\Data\SocialNetworkCustomer::SOCIAL_TYPE);
    }

    public function setSocialType($socialType)
    {
        $this->setData(\Techyouknow\SocialLogin\Api\Data\SocialNetworkCustomer::SOCIAL_TYPE, $socialType);

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->getData(\Techyouknow\SocialLogin\Api\Data\SocialNetworkCustomer::CREATED_AT);
    }

    public function setCreatedAt($createdAt)
    {
        $this->setData(\Techyouknow\SocialLogin\Api\Data\SocialNetworkCustomer::CREATED_AT, $createdAt);

        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->getData(\Techyouknow\SocialLogin\Api\Data\SocialNetworkCustomer::UPDATED_AT);
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->setData(\Techyouknow\SocialLogin\Api\Data\SocialNetworkCustomer::UPDATED_AT, $updatedAt);

        return $this;
    }

    public function getEntityId()
    {
        return $this->getData(\Techyouknow\SocialLogin\Api\Data\SocialNetworkCustomer::ENTITY_ID);
    }

    public function setEntityId($entityId)
    {
        $this->setData(\Techyouknow\SocialLogin\Api\Data\SocialNetworkCustomer::UPDATED_AT, $entityId);

        return $this;
    }
}