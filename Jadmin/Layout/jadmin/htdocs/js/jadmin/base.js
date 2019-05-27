(($) => {
    'use strict';

    const mainContent = document.querySelector('.main-content-wrap');

    if (!mainContent) console.warn('jadmin cannot access to main content');

    const manageTooltip = () => {

        const $tooltip = $('[data-toggle]');

        $tooltip.tooltip('dispose');
        $tooltip.tooltip({'boundary': 'window'});

    };

    function activeMenu(menu) {

        let items = menu.querySelectorAll('a');

        const navigate = async event => {

            event.preventDefault();
            event.stopPropagation();

            const url = event.currentTarget.href;

            try {

                let respuesta = await $.ajax({
                    'url': url,
                    'type': 'GET'
                });

                if (!respuesta) console.log('something wrong in call');
                mainContent.innerHTML = respuesta;
                window.setTimeout(() => manageTooltip(), 0);

            }
            catch (error) {
                console.error(error);
            }
        };

        items.forEach((item, index) => {

            if (!item.href || !mainContent) return;

            item.addEventListener('click', navigate);
        });
    }

    $('[data-toggle]').tooltip({boundary: 'window'});

    let menu = document.querySelector('.menu-jadmin');

    if (menu) activeMenu(menu);

})(jQuery);