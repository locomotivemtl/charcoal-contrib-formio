/* global Formio, Charcoal */
/**
 * Form builder
 *
 * Require
 * - formio.full.min.js
 */
;(function () {

    /**
     * `charcoal/admin/property/input/Formio/Form`
     * Property_Input_Formio_Form Javascript class
     *
     */
    var FormBuilder = function (data) {
        Charcoal.Admin.Property.call(this, data);

        this.set_properties(data).init();
    };

    FormBuilder.prototype             = Object.create(Charcoal.Admin.Property.prototype);
    FormBuilder.prototype.constructor = Charcoal.Admin.Property_Input_Formio_Submission;
    FormBuilder.prototype.parent      = Charcoal.Admin.Property.prototype;

    FormBuilder.prototype.set_properties = function (data) {
        // Builder reference
        this._builder         = undefined;
        this._builder_options = data.data.builder_options;
        this._schema          = data.data.schema;

        this.save_action   = data.save_action || 'object/save';
        this.update_action = data.update_action || 'object/update';

        // Elements
        this.$widget  = this.element();
        this.$builder = this.$widget.find('.js-form-builder');
        this.$input   = this.$widget.find('.js-input');
        this.$loader  = this.$widget.find('.js-loader');

        return this;
    };

    FormBuilder.prototype.init = function () {
        this.$loader.addClass('-is-loading');
        if (typeof Formio === "undefined") {
            console.log('Missing formio.full.js dependencies');

            return;
        }

        var options = this.parse_options();
        this.init_builder(options);

    };

    FormBuilder.prototype.parse_options = function () {
        var defaultOptions = {};

        this._builder_options = $.extend(true, defaultOptions, this._builder_options);

        return this._builder_options;
    };

    FormBuilder.prototype.init_builder = function (options) {
        var that           = this;
        var submissionData = this.$input.val() ? JSON.parse(this.$input.val()) : {};

        Formio.createForm(
            this.$builder.get(0),
            this._schema,
            options
        )
            .then(function (builder) {
                if (submissionData) {
                    builder.submission = {
                        data: submissionData
                    }
                }

                // On builder render
                that.$loader.removeClass('-is-loading');

                that._builder = builder;
            });
    };

    /**
     * I believe this should fit the PHP model
     * Added the save() function to be called on form submit
     * Could be inherited from a global Charcoal.Admin.Property Prototype
     * Extra ideas:
     * - save
     * - validate
     * @return this (chainable)
     */
    FormBuilder.prototype.save = function () {
        this.$input.val(JSON.stringify(this._builder.schema));
    };

    Charcoal.Admin.Property_Input_Formio_Submission = FormBuilder;

}(jQuery, document, Formio));
