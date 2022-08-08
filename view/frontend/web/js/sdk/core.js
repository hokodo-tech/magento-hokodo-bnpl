define([
    'HokodoSDK'
], function() {
    return {
        sdk: undefined,

        init() {
            return Hokodo("pk_test_g7ziU-hyBnm6oQmALykxnnwliwWmRj-TukvjZ3iKNvU", {
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
