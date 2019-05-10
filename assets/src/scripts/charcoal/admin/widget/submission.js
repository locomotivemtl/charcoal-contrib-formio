/* global Formio, Charcoal, submissionWidgetL10n */
/**
 * Form builder
 *
 * Require
 * - formio.full.min.js
 */
import FormioExport from '../../../node_modules/formio-export/lib/formio-export';

;(function () {
    /**
     * `charcoal/admin/property/input/Formio/Form`
     * Property_Input_Formio_Form Javascript class
     *
     */
    var Submission = function () {};

    Submission.prototype             = Object.create(Charcoal.Admin.Widget.prototype);
    Submission.prototype.constructor = Charcoal.Admin.Widget_Submission;
    Submission.prototype.parent      = Charcoal.Admin.Widget.prototype;

    Submission.prototype.set_properties = function () {
        var opts = this.opts();

        // Builder reference
        this._builder         = undefined;
        this._builder_options = opts.data.builder_options;
        this._schema          = opts.data.schema;
        this._submission      = opts.data.submission;

        // Elements
        this.$widget    = this.element();
        this.$builder   = this.$widget.find('.js-form-builder');
        this.$loader    = this.$widget.find('.js-loader');
        this.$pdf_button = this.$widget.find('.js-pdf-download');

        return this;
    };

    Submission.prototype.init = function () {
        this.set_properties();

        this.$loader.addClass('-is-loading');
        if (typeof Formio === "undefined") {
            console.log('Missing formio.full.js dependencies');

            return;
        }

        var options = this.parse_options();
        this.init_builder(options);

        this.$pdf_button.on('click', this.downloadPdf.bind(this));
    };

    Submission.prototype.downloadPdf = function () {
        var FormExportData = $.extend(true, this._schema, {
            type:       'form',
            title:      'Soumission',
            display:    'form',
            viewAsHtml: true
        });
        var exporter       = new FormioExport(FormExportData, this._submission, {
            viewAsHtml: true,
            formio:     {
                viewAsHtml:   true,
                ignoreLayout: false
            }
        });

        var pdf_config = {
            download: true,
            filename: submissionWidgetL10n.submission+'.pdf'
        };

        exporter.toPdf(pdf_config);
    };

    Submission.prototype.parse_options = function () {
        var defaultOptions = {};

        this._builder_options = $.extend(true, defaultOptions, this._builder_options);

        return this._builder_options;
    };

    Submission.prototype.init_builder = function (options) {
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

    Charcoal.Admin.Widget_Submission = Submission;

}(jQuery, document, Formio, FormioExport));
