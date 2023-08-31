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

 namespace Techyouknow\SocialLogin\Controller\Social;

 use Magento\Framework\App\Action\Context;
 use Magento\Framework\Controller\Result\RawFactory;
 use Techyouknow\SocialLogin\Model\Social;
 use Magento\Customer\Api\CustomerRepositoryInterface;
 use Magento\Customer\Model\CustomerFactory;
 use Techyouknow\SocialLogin\Api\SocialNetworkCustomerRepositoryInterface;
 
 class Login extends \Magento\Framework\App\Action\Action
 {
     private $resultRawFactory;
     private $socialModel;
     private $customerRepository;
     private $customerModelFactory;
     private $socialNetworkCustomerRepository;
 
     public function __construct(
         Context $context,
         RawFactory $resultRawFactory,
         Social $socialModel,
         CustomerRepositoryInterface $customerRepository,
         CustomerFactory $customerModelFactory,
         SocialNetworkCustomerRepositoryInterface $socialNetworkCustomerRepository
     )
     {
         parent::__construct($context);
         $this->resultRawFactory = $resultRawFactory;
         $this->socialModel = $socialModel;
         $this->customerRepository = $customerRepository;
         $this->customerModelFactory = $customerModelFactory;
         $this->socialNetworkCustomerRepository = $socialNetworkCustomerRepository;
     }

    public function execute()
    {
        $adapterId = $this->getRequest()->getParam('provider');
        
        try {
            $userProfile = $this->socialModel->getSocialUserProfile($adapterId);
            $customer = $this->customerRepository->get($userProfile['email']);

            if(isset($customer) && $customer->getId()) {
                $customerModel = $this->customerModelFactory->create()->load($customer->getId());
                
                if(!$this->socialNetworkCustomerRepository->socialNetworkCustomerExists($userProfile, $adapterId)) {
                    $this->socialModel->createSocialLoginCustomer($userProfile, $adapterId, $customer->getId());
                }
            }
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $customerModel = $this->socialModel->createCustomerAccount($userProfile, $adapterId);
        } catch (\Exception $e) {
            exit("Error: " . $e->getMessage());
        }

        $this->socialModel->refresh($customerModel);

        return $this->_appendJs();
    }

    /**
     * @param null $content
     * @return mixed
     */
    public function _appendJs($content = null)
    {
        /** @var Raw $resultRaw */
        $resultRaw = $this->resultRawFactory->create();

        $raw = $resultRaw->setContents($content ?:
            "<script>
                    window.opener.location.reload(true);
                    window.close();
                </script>");

        return $raw;
    }
}