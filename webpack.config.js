const path = require('path');

module.exports = {
  entry: './lib/management/appointments/appointments.js',
  output: {
    filename: 'bundle.js',
    path: path.resolve(__dirname, 'dist')
  }
};