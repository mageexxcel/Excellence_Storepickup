define([
    'uiComponent',
    'Magento_Checkout/js/model/quote',
    'ko',
], function (Component,quote,ko) {
    'use strict';
    return Component.extend({
        defaults: {
            template: 'Excellence_Storepickup/storepickup'
        },
        isVisible : ko.observable(false),
        storepickups: ko.observableArray(window.storepickup),
        mapLink: ko.observable(window.mapLink),
        linkText: ko.observable(window.linkText),
        initialize: function(){
           var self = this;
           self._super();
           var url = window.storelocUrl;
           quote.shippingMethod.subscribe(function(method){
              self.toggleBox(method);
           });
           self.toggleBox(quote.shippingmethod);
           return self;
        },
        toggleBox : function(method){
          var self = this;
          if(method){
            if(method.method_code == 'storepickup'){
               self.isVisible(true);
             }else{
               self.isVisible(false);
             }
          }
        }
    });
});