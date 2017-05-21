var Storage = {
	set : function(key, value) {

		if ( typeof value == 'object')
			value = JSON.stringify(value);
		localStorage[key] = value;
	},

	obt : function(key) {
		if (localStorage.getItem(key) !== null) {
			return localStorage.getItem(key);
		}
		return false;
	},

	obtJson : function(key) {
		if (this.obt(key)) {
			return JSON.parse(this.obt(key));
		}
	},

	borrar : function(key) {
		localStorage.removeItem(key);
		return this;
	}
};
var menuConfig = {
	showMenu : true
};
function setLinkMenuClass($linkToggle) {
	if ($linkToggle.hasClass('fa-arrow-right')) {
		$linkToggle.find('span.fa').removeClass('fa-arrow-right').addClass('fa-arrow-left');
	} else {
		$linkToggle.find('span.fa').removeClass('fa-arrow-left').addClass('fa-arrow-right');
	}
}

function addMenuTooltip() {

	$('.menu li a').each(function(k, item) {
		var $item = $(item);
		var $icon = $item.find('.fa');
		var $text = $item.find('.inner-text');

		$text.parent().attr({
			'data-toggle' : 'tooltip',

			'data-placement' : 'right',
			'title' : $.trim($text.html())
		});

	});
}

function removeMenuTooltip() {
	$('.menu li a').each(function(k, item) {
		var $item = $(item);
		var $text = $item.find('.inner-text');
		$text.parent().removeAttr('data-toggle');

	});
	$('.tooltip').remove();
}

function toggleMenu(open) {
	if (!open)
		open = false;
	var $content = $('#content-wrapper');
	var menuConfig = Storage.obtJson('menuAdmin');

	if (!open) {
		if (!$content.hasClass('short-menu')) {
			$content.addClass('short-menu');
			menuConfig.showMenu = false;
			addMenuTooltip();
		}
	} else {
		$('.li-parent').removeClass('selected').find('ul').removeClass('show');
		if ($content.hasClass('short-menu')) {
			$content.removeClass('short-menu');
			menuConfig.showMenu = true;
			removeMenuTooltip();
		} else {
			$content.addClass('short-menu');
			menuConfig.showMenu = false;
		}
	}

	if ($content.hasClass('short-menu')) {
		addMenuTooltip();
	} else {
		$('aside li a').each(function(item, key) {
			var $item = $(item);
			$item.removeAttr('data-toggle');
		});

	}
	Storage.set('menuAdmin', menuConfig);
}

function processUrl(key, value) {
	key = encodeURI(key);
	value = encodeURI(value);

	var kvp = document.location.search.substr(1).split('&');

	var i = kvp.length;
	var x;
	while (i--) {
		x = kvp[i].split('=');

		if (x[0] == key) {
			x[1] = value;
			kvp[i] = x.join('=');
			break;
		}
	}
	if (i < 0) {
		kvp[kvp.length] = [key, value].join('=');
	}
	return kvp.join('&');

}

(function($) {
	console.log('Jida Administrador');
	/**
	 * Variable en sesion de la configuracion menuConfig
	 * @see menuConfig
	 */
	var dataMenu = Storage.obtJson('menuAdmin');
	if (dataMenu) {
		toggleMenu(dataMenu.showMenu);
	} else {
		Storage.set('menuAdmin', menuConfig);
	}

	var $linkToggle = $('.menu-toggle');
	if ($('#content-wrapper').hasClass('short-menu'))
		;
	addMenuTooltip();

	$('.li-parent').on('click', function(e) {

		var $this = $(this);
		if ($this.find('ul').length > 1) {

			setLinkMenuClass($linkToggle);
			toggleMenu(true);
		}

	});

	console.log(dataMenu);

	$linkToggle.on('click', function(e) {
		
		e.preventDefault();
		setLinkMenuClass($linkToggle);
		band = (dataMenu.showMenu) ? false : true;
		toggleMenu(band);

	});

	$("body").tooltip({
		selector : '[data-toggle="tooltip"]',
		placement : 'right'
	});

})(jQuery);

