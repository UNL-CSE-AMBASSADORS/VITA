// Adapted from https://stackoverflow.com/a/44074265
// This can be used to use an ng-repeat with a Javascript Map
define('fromMapFilter', [], () => {
	return () => {
		return (input) => {
			let out = {};
			input.forEach((v, k) => out[k] = v);
			return out;
		}
	}
});
