const path = require('path');
const webpack = require('webpack');

module.exports = {
	entry: {
		  appointments: './management/appointments/appointments.js',
		  login: './login/login.js',
		  queue: './queue/queue.js',
		  queue_private: './queue/queue_private.js',
		  queue_service: './queue/queue_service.js',
		  register: './register/register.js',
		  signup: './signup/signup.js'
	},
	output: {
		filename: '[name]_bundle.js',
		path: path.resolve(__dirname, 'dist')
	},
	module: {
		loaders: [
			{
				test: /\.js$/,
				loader: 'babel-loader'
			}
		]
	},
	plugins: [
		
	]
};

if(process.env.NODE_ENV === 'production'){
	module.exports.plugins.push(new webpack.optimize.UglifyJsPlugin())
}