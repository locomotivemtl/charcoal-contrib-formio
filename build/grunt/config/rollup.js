var babel = require('rollup-plugin-babel');
var common = require('rollup-plugin-commonjs');
var resolve = require('rollup-plugin-node-resolve');

module.exports = {
    options: {
        plugins: [
            resolve(),
            babel({
                exclude: './node_modules/**'
            }),
            common({
                include: './node_modules/**'
            }),
        ]
    },
    files: {
        'dest':'<%= paths.js.dist %>/formio.js',
        'src' : '<%= paths.js.dist %>/formio.js',
    },
};
