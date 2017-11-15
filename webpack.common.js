const path = require('path');

module.exports = {
	entry: {
		// 'output_path/name': 'entry_path/name'
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
				exclude: /node_modules/,
				loader: 'babel-loader'
			}
		]
	}
};