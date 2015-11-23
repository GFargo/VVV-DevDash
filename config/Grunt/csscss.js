module.exports = {
	options: {
	  colorize: true,
	  verbose: false, // Logs out Output
	  minMatch: 5,
	  failWhenDuplicates: false,
	  shorthand: true, // Uniquely identifies Padding/Margin/Font v.s 'Padding-left' & Padding
	  ignoreProperties: [''], // Ignore specific properties i.e. 'padding' or 'background'
	  ignoreSelectors: [''], // Ignore specific selectors i.e. '.button'
	  ignoreSassMixins: false,
	  outputJson: false, // Saves Results in 'prep-log' Json File
	  // compass: true, // For Compass Projects
	  // require: '<%= dirs.webroot %><%= dirs.site %>/config.rb', // Required for Compass Projects
	},
	build: {
		src: ['<%= project.paths.css %>/*.css'],
	},
	check: {
	  files: {
	    'csscss-log.json': ['<%= project.paths.css %>/*.css']
	  }
	},
};