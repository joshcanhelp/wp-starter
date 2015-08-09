/* globals jQuery, alert, console, module, pccLocalVars */

var $ = jQuery;

module.exports = {

	/**
	 * Sets column height to be equal for children nodes
	 *
	 * @param wrapper
	 */
	equalColHeight:  function (wrapper) {
		var colHeight = 0;
		wrapper.children().each(function (index, el) {
			var thisHeight = $(el).height();
			if (thisHeight > colHeight) {
				colHeight = thisHeight;
			}
		});
		wrapper.children().height(colHeight);
	}
};
