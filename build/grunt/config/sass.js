module.exports = {
    options: {
        sourceMap:   false,
        outputStyle: 'expanded'
    },
    vendors: {
        files: {
            '<%= paths.css.dist %>/charcoal.formio.vendors.css': '<%= paths.css.src %>/**/charcoal.formio.vendors.scss'
        }
    }
};
