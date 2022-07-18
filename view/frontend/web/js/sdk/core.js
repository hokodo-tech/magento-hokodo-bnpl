define([
    'HokodoSDK'
], function() {
    return {
        sdk: undefined,

        init() {
            return Hokodo("pk_test_6H224CyVnIgrsP6o5IDnFoTT-CB5YSXEvOQ8idk_QzQ", {
                locale: "en"
            });
        },

        getSdk() {
            if (!this.sdk) {
                this.sdk = this.init()
            }
            return this.sdk;
        }
    }
})
