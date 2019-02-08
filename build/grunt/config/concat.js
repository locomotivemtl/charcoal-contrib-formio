module.exports = {
    options: {
        separator: ';'
    },
    formio: {
        src: [
            '<%= paths.js.src %>/**/*.js',
        ],
        dest: '<%= paths.js.dist %>/formio.js'
    },
    vendors: {
        src:       [
            // Formio.js
            '<%= paths.npm %>/formiojs/dist/formio.full.min.js',
        ],
        dest:      '<%= paths.js.dist %>/charcoal.formio.vendors.js',
        separator: "\n"
    }
};
