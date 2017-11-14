const path = require('path');
const webpack = require('webpack');

module.exports = {
	entry: {
		// 'output_path/name': 'entry_path/name'
		'/management/appointments/build/appointment': './management/appointments/appointments.js',
		'/login/build/login': './login/login.js',
		'/queue/build/queue': './queue/queue.js',
		'/queue/build/queue_private': './queue/queue_private.js',
		'/queue/build/queue_service': './queue/queue_service.js',
		'/register/build/register': './register/register.js',
		'/signup/build/signup': './signup/signup.js'
	},
	output: {
		filename: '[name]_bundle.js',
		path: __dirname
	},
	module: {
		loaders: [
			{
				test: /\.js$/,
				exclude: /node_modules/,
				loader: 'babel-loader'
			}
		]
	},
	plugins: [
		// Injects in Uglify when running in production
	]
};

if(process.env.NODE_ENV === 'production'){
	module.exports.plugins.push(new webpack.optimize.UglifyJsPlugin())
}