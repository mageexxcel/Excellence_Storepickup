/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*global define,alert*/
define(
    [
        'ko',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/resource-url-manager',
        'mage/storage',
        'Magento_Checkout/js/model/payment-service',
        'Magento_Checkout/js/model/payment/method-converter',
        'Magento_Checkout/js/model/error-processor',
        'Magento_Checkout/js/model/full-screen-loader',
        'Magento_Checkout/js/action/select-billing-address',
        'uiRegistry',
        'mage/url',
        'jquery'

    ],
    function (
        ko,
        quote,
        resourceUrlManager,
        storage,
        paymentService,
        methodConverter,
        errorProcessor,
        fullScreenLoader,
        selectBillingAddressAction,
        registry,
        urlBuilder,
        $

    ) {
        'use strict';
        return {
            saveShippingInformation: function () {
                var payload;

                if (!quote.billingAddress()) {
                    selectBillingAddressAction(quote.shippingAddress());
                }


                payload = {
                    addressInformation: {
                        shipping_address: quote.shippingAddress(),
                        billing_address: quote.billingAddress(),
                        shipping_method_code: quote.shippingMethod().method_code,
                        shipping_carrier_code: quote.shippingMethod().carrier_code
                        
                    }
                };

                    fullScreenLoader.startLoader();
                    var storepickup = $('[name="storebase"]').val();
                        return  $.ajax({ cache: false,
                            url: urlBuilder.build('storepickup/index/pickup/'),
                            type: 'POST',
                            data: { storepickup: storepickup}
                        }).done(function(){
                            $('#pickupStr').hide();
                                    storage.post(
                                        resourceUrlManager.getUrlForSetShippingInformation(quote),
                                        JSON.stringify(payload)
                                    ).done(
                                        function (response) {
                                            quote.setTotals(response.totals);
                                            paymentService.setPaymentMethods(methodConverter(response.payment_methods));
                                            fullScreenLoader.stopLoader();
                                        }
                                    ).fail(
                                        function (response) {
                                            errorProcessor.process(response);
                                            fullScreenLoader.stopLoader();

                                            return $.when();
                                        }
                                    );
                        }).fail(function(){
                            errorProcessor.process(response);
                            fullScreenLoader.stopLoader();
                        });   

            }
        };
    }
);
