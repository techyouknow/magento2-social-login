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

namespace Techyouknow\SocialLogin\Model\Repository;


class SocialLoginCustomerRepository implements \Techyouknow\SocialLogin\Api\SocialNetworkCustomerRepositoryInterface
{


    private $collectionFactory;
    private $socialLoginCustomerFactory;

    public function __construct(
        \Techyouknow\SocialLogin\Model\ResourceModel\SocialLoginCustomer\CollectionFactory $collectionFactory,
        \Techyouknow\SocialLogin\Model\SocialLoginCustomerFactory $socialLoginCustomerFactory
    )
    {

        $this->collectionFactory = $collectionFactory;
        $this->socialLoginCustomerFactory = $socialLoginCustomerFactory;
    }

    public function getById($id)
    {
        $socialLoginCustomer = $this->socialLoginCustomerFactory->create();
        $socialLoginCustomer->getResource()->load($socialLoginCustomer, $id);

        if(!$socialLoginCustomer->getId()) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(__("Unable to find Social Login Customer with ID %1", $id));
        }

        return $socialLoginCustomer;
    }

    public function socialNetworkCustomerExists($userProfile, $type)
    {
        $collection = $this->collectionFactory->create()
            ->addFieldToFilter('social_id', $userProfile['identifier'])
            ->addFieldToFilter('social_type', $type);

        return $collection->count();
    }

    public function save(\Techyouknow\SocialLogin\Api\Data\SocialNetworkCustomer $socialNetworkCustomer)
    {
        $socialNetworkCustomer->getResource()->save($socialNetworkCustomer);

        return $socialNetworkCustomer;
    }

    public function delete(\Techyouknow\SocialLogin\Api\Data\SocialNetworkCustomer $socialNetworkCustomer)
    {
        $socialNetworkCustomer->getResource()->delete($socialNetworkCustomer);

        return $socialNetworkCustomer;
    }
}