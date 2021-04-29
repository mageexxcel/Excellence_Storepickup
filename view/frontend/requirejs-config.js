/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var config = {
    "map": {
        "*": {
        	"Magento_Checkout/template/shipping-information.html": "Excellence_Storepickup/template/shipping-information.html",
        	'Magento_Checkout/js/view/shipping-information':'Excellence_Storepickup/js/view/shipping-information',
        	'Magento_Checkout/js/view/shipping-information/list':'Excellence_Storepickup/js/view/shipping-information/list',
            "Magento_Checkout/js/model/shipping-save-processor/default" : "Excellence_Storepickup/js/shipping-save-processor-default-override"
        }
    }
};