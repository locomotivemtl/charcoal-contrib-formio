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
        // Charcoal.Admin.Property.call(this, data);
    };

    FormBuilder.prototype             = Object.create(Charcoal.Admin.Widget.prototype);
    FormBuilder.prototype.constructor = Charcoal.Admin.Widget_Submission;
    FormBuilder.prototype.parent      = Charcoal.Admin.Widget.prototype;

    FormBuilder.prototype.set_properties = function () {
        var opts = this.opts();

        // Builder reference
        this._builder         = undefined;
        this._builder_options = opts.data.builder_options;
        this._schema          = opts.data.schema;
        this._submission      = opts.data.submission;

        // Elements
        this.$widget  = this.element();
        this.$builder = this.$widget.find('.js-form-builder');
        this.$loader  = this.$widget.find('.js-loader');

        return this;
    };

    FormBuilder.prototype.init = function () {
        this.set_properties();

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
        var submissionData = this._submission;

        Formio.createForm(
            this.$builder.get(0),
            this._schema,
            options
        )
            .then(function (builder) {
                if (submissionData) {
                    builder.submission = {
                        data: submissionData
                    };
                }

                // On builder render
                that.$loader.removeClass('-is-loading');

                that._builder = builder;
            });
    };

    Charcoal.Admin.Widget_Submission = FormBuilder;

}(jQuery, document, Formio));
