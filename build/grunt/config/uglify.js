module.exports = {
    options: {
        banner: '/*! <%= package.name %> <%= grunt.template.today("dd-mm-yyyy") %> */\n'
    },
    app: {
        files: {
            '<%= paths.js.dist %>/charcoal.formio.min.js': [
                '<%= paths.js.dist %>/formio.js'
            ]
        }
    },
    vendors: {
        files: {
            '<%= paths.js.dist %>/charcoal.formio.vendors.min.js': [
                '<%= concat.formio_vendor.dest %>'
            ]
        }
    }
};
