/**
 * All of the code for your admin-facing JavaScript source
 * should reside in this file.
 *
 * Note: It has been assumed you will write jQuery code here, so the
 * $ function reference has been prepared for usage within the scope
 * of this function.
 *
 * This enables you to define handlers, for when the DOM is ready:
 *
 * $(function() {
 *
 * });
 *
 * When the window is loaded:
 *
 * $( window ).load(function() {
 *
 * });
 *
 * ...and/or other possibilities.
 *
 * Ideally, it is not considered best practise to attach more than a
 * single DOM-ready or window-load handler for a particular page.
 * Although scripts in the WordPress core, Plugins and Themes may be
 * practising this, we should strive to set a better example in our own work.
 */

const { __, _x, _n, sprintf } = wp.i18n;

(function ($) {
    'use strict';

    window.onload = function() {
        const siteUrl = document.getElementById('ixyt_url');

        if (siteUrl) {
            ixyt_get_countries(siteUrl.value + 'api');
        }
    }
})(jQuery);

async function ixyt_get_countries(apiUrl) {
    try {
        const locale =  document.getElementById('ixyt_locale').value;
        const data = await fetch(apiUrl+'/get-countries?'+
        new URLSearchParams({locale:locale})
            ,{
            method: 'GET',

            headers: {
                'Content-Type': 'application/json',
            }
        }).then((response) => response.json())
            .then((data) => {
                return data
            });

        let select = document.getElementById('ixyt-country-select');

        let opt = document.createElement('option');
        opt.innerHTML = __( 'choose country', 'world-city-events-ixyt' );
        opt.disabled = true;
        opt.selected = true;
        select.appendChild(opt);

        const countryValue = document.getElementById('country-meta').value;

        if(countryValue){
            ixyt_get_cities(countryValue,apiUrl)
        }

        data.forEach(item => {
            let opt = document.createElement('option');
            opt.value = item.country_code;
            opt.innerHTML = item.name;
            if (countryValue && item.name_en === countryValue) {
                opt.selected = true;
            }
            select.appendChild(opt);
        })

        select.onchange = function () {
            ixyt_get_cities(select.value,apiUrl);
            document.getElementById('country-meta_loc').value = select.options[select.selectedIndex].text;
            document.getElementById('city-meta_loc').value = '';
        }

    } catch (error) {
        // TypeError: Failed to fetch
        console.log('There was an error', error);
    }

}

async function ixyt_get_cities(country,api) {
    try {
        const locale =  document.getElementById('ixyt_locale').value;
        const data = await fetch(api+'/get-cities-2?' +
            new URLSearchParams({locale:locale,country:country})
        , {
            method: 'GET', headers: {
                'Content-Type': 'application/json',
            }
        }).then((response) => response.json())
            .then((data) => {
                return data
            });

        const select = document.getElementById('ixyt-city-select');

        select.innerHTML = '';
        const cityValue = document.getElementById('city-meta').value;

        data.forEach(item => {
            let opt = document.createElement('option');
            opt.value = item.city;
            opt.innerHTML = item.name;
            if (cityValue && item.city === cityValue) {
                opt.selected = true;
            }
            select.appendChild(opt);
        })

        select.style.display = 'block';
        select.onchange = function () {
            console.log(select,select.value,select.options[select.selectedIndex].text);
            document.getElementById('city-meta_loc').value = select.options[select.selectedIndex].text;
        }
    } catch (error) {
        // TypeError: Failed to fetch
        console.log('There was an error', error);
    }
}

