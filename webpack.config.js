const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );

module.exports = {
	...defaultConfig,
	entry: {
		frontend: './assets/js/src/frontend.js',
		admin: './assets/js/src/admin.js',
	},
	output: {
		...defaultConfig.output,
		path: path.resolve( __dirname, 'assets/js/build' ),
		filename: '[name].js',
	}
};
