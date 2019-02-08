module.exports = {
    options: {
        sourceMap:   false,
        outputStyle: 'expanded'
    },
    app: {
        files: {
            '<%= paths.css.dist %>/charcoal.formio.css': '<%= paths.css.src %>/**/charcoal.formio.scss'
        }
    },
    vendors: {
        files: {
            '<%= paths.css.dist %>/charcoal.formio.vendors.css': '<%= paths.css.src %>/**/charcoal.formio.vendors.scss'
        }
    }
};
