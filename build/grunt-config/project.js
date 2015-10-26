module.exports = {
	paths: {
		webroot: '',
		sass: 'assets/scss',
		css: 'assets/css',
		js: 'assets/js',
	},
	banner:
		'/*!\n' +
		' * <%= pkg.title %>\n' +
		' * <%= pkg.description %>\n' +
		' * <%= pkg.url %>\n' +
		' * @author <%= pkg.author %>\n' +
		' * @version <%= pkg.version %>\n' +
		' */\n\n\n',
};