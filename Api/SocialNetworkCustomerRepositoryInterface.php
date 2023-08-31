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

namespace Techyouknow\SocialLogin\Api;

/**
 * Interface SocialNetworkCustomerRepositoryInterface
 * @package Techyouknow\SocialLogin\Api
 */
interface SocialNetworkCustomerRepositoryInterface
{
    /**
     * Get social network customer by ID.
     *
     * @param int $id
     * @return \Techyouknow\SocialLogin\Api\Data\SocialNetworkCustomer
     */
    public function getById($id);

    /**
     * Save social network customer.
     *
     * @param \Techyouknow\SocialLogin\Api\Data\SocialNetworkCustomer $socialNetworkCustomer
     * @return void
     */
    public function save(\Techyouknow\SocialLogin\Api\Data\SocialNetworkCustomer $socialNetworkCustomer);

    /**
     * Delete social network customer.
     *
     * @param \Techyouknow\SocialLogin\Api\Data\SocialNetworkCustomer $socialNetworkCustomer
     * @return void
     */
    public function delete(\Techyouknow\SocialLogin\Api\Data\SocialNetworkCustomer $socialNetworkCustomer);
}
