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


use Magento\Framework\App\ObjectManager;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\Cookie\PhpCookieManager;

class Social extends \Magento\Framework\Model\AbstractModel
{

    private $socialHelper;
    private $customerFactory;
    private $customerRepository;
    private $storeManager;
    private $cookieMetadataFactory;
    private $cookieMetadataManager;
    private $session;
    private $customerModelFactory;
    private $accountManagement;
    private $random;
    private $socialLoginCustomerRepository;
    private $socialNetworkCustomer;
    private $dateTime;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Techyouknow\SocialLogin\Helper\Social $socialHelper,
        \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerFactory,
        \Magento\Customer\Model\CustomerFactory $customerModelFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory,
        \Magento\Framework\Stdlib\Cookie\PhpCookieManager $cookieMetadataManager,
        \Magento\Customer\Model\Session $session,
        \Magento\Customer\Model\AccountManagement $accountManagement,
        \Magento\Framework\Math\Random $random,
        \Techyouknow\SocialLogin\Api\Data\SocialNetworkCustomerFactory $socialNetworkCustomer,
        \Techyouknow\SocialLogin\Model\Repository\SocialLoginCustomerRepository $socialLoginCustomerRepository,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->socialHelper = $socialHelper;
        $this->customerFactory = $customerFactory;
        $this->customerRepository = $customerRepository;
        $this->storeManager = $storeManager;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->cookieMetadataManager = $cookieMetadataManager;
        $this->session = $session;
        $this->customerModelFactory = $customerModelFactory;
        $this->accountManagement = $accountManagement;
        $this->random = $random;
        $this->socialLoginCustomerRepository = $socialLoginCustomerRepository;
        $this->socialNetworkCustomer = $socialNetworkCustomer;
        $this->dateTime = $dateTime;
    }

    /**
     * @param $adapterId
     * @return \Hybridauth\User\Profile
     * @throws \Hybridauth\Exception\InvalidArgumentException
     * @throws \Hybridauth\Exception\UnexpectedValueException
     */
    public function getSocialUserProfile($adapterId) {
        $adapterName = $this->socialHelper->getSocialNetwork($adapterId);
        $adaptersConfig = $this->socialHelper->getHybridauthConfig($adapterId);

        $hybridauth = new \Hybridauth\Hybridauth($adaptersConfig);

        // Attempt to authenticate the user
        $adapter = $hybridauth->authenticate($adapterName);

        // Retrieve the user's profile
        $userProfile = $adapter->getUserProfile();

        // Disconnect the adapter (log out)
        $adapter->disconnect();

        return $this->prepareUserProfile($userProfile, $adapterId);
    }

    public function prepareUserProfile($userProfile, $type)
    {
        $name = explode(' ', $userProfile->displayName ?: __('New User'));

        return [
            'email' => $userProfile->email ?: $userProfile->identifier . '@' . strtolower($type) . '.com',
            'firstname' => $userProfile->firstName ?: (array_shift($name) ?: $userProfile->identifier),
            'lastname' => $userProfile->lastName ?: (array_shift($name) ?: $userProfile->identifier),
            'identifier' => $userProfile->identifier,
            'type' => $type,
            'password' => isset($userProfile->password) ? $userProfile->password : null
        ];
    }

    public function createCustomerAccount($userProfile, $type) {
        $store = $this->storeManager->getStore();

        $customer = $this->customerFactory->create();
        $customer
            ->setFirstname($userProfile['firstname'])
            ->setLastname($userProfile['lastname'])
            ->setEmail($userProfile['email'])
            ->setStoreId($store->getId())
            ->setWebsiteId($store->getWebsiteId())
            ->setCreatedIn($store->getName());

        $customer = $this->customerRepository->save($customer);
        $this->createSocialLoginCustomer($userProfile, $type, $customer->getId());

        // Update rp_token & rk_token_created_at columns
        $newPasswordToken  = $this->random->getUniqueHash();
        $this->accountManagement->changeResetPasswordLinkToken($customer, $newPasswordToken);

        return $this->customerModelFactory->create()->load($customer->getId());
    }

    public function createSocialLoginCustomer($userProfile, $type, $customerId) {
        $socialNetworkCustomer = $this->socialNetworkCustomer->create();
        $currentTimestamp = $this->dateTime->timestamp();

        $socialNetworkCustomer
            ->setSocialId($userProfile['identifier'])
            ->setCustomerId($customerId)
            ->setSocialType($type)
            ->setCreatedAt($currentTimestamp)
            ->setUpdatedAt($currentTimestamp);

        $this->socialLoginCustomerRepository->save($socialNetworkCustomer);
    }

    public function refresh($customer)
    {
        if ($customer && $customer->getId()) {
            $this->session->setCustomerAsLoggedIn($customer);
            $this->session->regenerateId();

            if ($this->getCookieManager()->getCookie('mage-cache-sessid')) {
                $metadata = $this->getCookieMetadataFactory()->createCookieMetadata();
                $metadata->setPath('/');
                $this->getCookieManager()->deleteCookie('mage-cache-sessid', $metadata);
            }
        }
    }

    /**
     * Retrieve cookie manager
     *
     * @return     PhpCookieManager
     * @deprecated
     */
    private function getCookieManager()
    {
        if (!$this->cookieMetadataManager) {
            $this->cookieMetadataManager = ObjectManager::getInstance()->get(
                PhpCookieManager::class
            );
        }

        return $this->cookieMetadataManager;
    }

    /**
     * Retrieve cookie metadata factory
     *
     * @return     CookieMetadataFactory
     * @deprecated
     */
    private function getCookieMetadataFactory()
    {
        if (!$this->cookieMetadataFactory) {
            $this->cookieMetadataFactory = ObjectManager::getInstance()->get(
                CookieMetadataFactory::class
            );
        }

        return $this->cookieMetadataFactory;
    }
}