// Adapted from https://stackoverflow.com/a/44074265
define('fromMapFilter', [], () => {
	return () => {
		return (input) => {
			let out = {};
			input.forEach((v, k) => out[k] = v);
			return out;
		}
	}
});
