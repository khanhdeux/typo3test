var extbaseModeling_wiringEditorLanguage = {
	parentEl: 'domainModelEditor',
	languageName: "extbaseModeling",
	smdUrl: TYPO3.settings.ajaxUrls['ExtensionBuilder::wiringEditorSmdEndpoint'],
	layerOptions: {
	},
	modules: []
};

function extbaseModeling_updateWires() {
    console.log('extbaseModeling_updateWires');
    var containers = YAHOO.util.Dom.getElementsByClassName('WireIt-Layer')[0].layer.containers;
    for (var i = 0; i < containers.length; i++) {
        containers[i].dd.onDrag();
    }
}

(function(){
	var inputEx = YAHOO.inputEx, Event = YAHOO.util.Event, lang = YAHOO.lang, dom = YAHOO.util.Dom;

		function addFieldsetClass (selectElement) {
			var fieldset = dom.getAncestorByTagName(selectElement, 'fieldset');
			if (dom.hasClass(fieldset, 'inputEx-Expanded')) {
				return;
			}
			fieldset.removeAttribute('class');
			dom.addClass(fieldset, selectElement.value);
		}

		inputEx.SelectField.prototype.onChange = function (evt) {
			addFieldsetClass(dom.get(evt.target));
		};

		/**
		 * add the selected propertyType as classname to all propertyGroup fieldsets
		 */
		WireIt.WiringEditor.prototype.onPipeLoaded = function () {
			var propertyTypeSelects = $$('.propertyGroup select');
			if (propertyTypeSelects) {
				propertyTypeSelects.each(function (el) {
					addFieldsetClass(dom.get(el));
				});
			}
			$$('.inputEx-ListField-Arrow').each(function (el) {
				return $(el).observe('click', function() {
					extbaseModeling_updateWires();
				});
			});
		};
})();


YAHOO.util.Event.onAvailable('toggleAdvancedOptions', function () {

	$('typo3-mod-php').addClassName('yui-skin-sam');

	var advancedMode = false;
	$('toggleAdvancedOptions').onclick =
	function (ev, target) {
		if (!advancedMode) {
			$('domainModelEditor').addClassName('showAdvancedOptions');
			$$('#toggleAdvancedOptions .simpleMode')[0].style.display = 'none';
			$$('#toggleAdvancedOptions .advancedMode')[0].style.display = 'inline';
			advancedMode = true;
		} else {
			$('domainModelEditor').removeClassName('showAdvancedOptions');
			$$('#toggleAdvancedOptions .simpleMode')[0].style.display = 'inline';
			$$('#toggleAdvancedOptions .advancedMode')[0].style.display = 'none';
			advancedMode = false;
		}
		extbaseModeling_updateWires();
		return false;
	};
});