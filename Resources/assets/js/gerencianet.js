"use strict";

//sales.customers
let gerencianet_tax_number = document.getElementById('tax_number');
let gerencianet_address = document.getElementById('address');
let gerencianet_city = document.getElementById('city');
let gerencianet_state = document.getElementById('state');
let gerencianet_zip_code = document.getElementById('zip_code');

function setRequired(element, parentElement) {
    let div_class = parentElement.getAttribute('class');

    div_class += ' required';
    parentElement.setAttribute('class', div_class);

    if(element.required === false) {
        element.setAttribute('required', 'required');
    }
}

if(gerencianet_tax_number !== null) {
    setRequired(
        gerencianet_tax_number,
        gerencianet_tax_number.parentElement.parentElement
    );

    gerencianet_tax_number.setAttribute('maxlength', '14');
    gerencianet_tax_number.setAttribute(
        'onkeypress',
        'return keyPressOnlyNumber(event)'
    );
}

if(gerencianet_address !== null) {
    setRequired(
        gerencianet_address,
        gerencianet_address.parentElement
    );
}

if(gerencianet_city !== null) {
    setRequired(
        gerencianet_city,
        gerencianet_city.parentElement.parentElement
    );
}

if(gerencianet_state !== null) {
    setRequired(
        gerencianet_state,
        gerencianet_state.parentElement.parentElement
    );

    gerencianet_state.setAttribute('maxlength', '2');
}

if(gerencianet_zip_code !== null) {
    setRequired(
        gerencianet_zip_code,
        gerencianet_zip_code.parentElement.parentElement
    );

    gerencianet_zip_code.setAttribute('maxlength', '9');
    gerencianet_zip_code.setAttribute(
        'onkeypress',
        'return keyPressFormatCEP(event)'
    );
}

// Return key code
function getKey(e) {
	return window.event ? event.keyCode : e.which;
}

// Return key pressed only if is number
function keyPressOnlyNumber(e) {
	var key = getKey(e);

	if(key > 47 && key < 58) {
        return true;
    }
	else {
		if (key == 8 || key == 0){
            return true;
        }
		else {
            return false;
        }
	}
}

// Return key pressed to format Brazillian zip code
function keyPressFormatCEP(e) {
    let path = e.path || (e.composedPath && e.composedPath());
    if(path[0].value.length == 5) {
        path[0].value += '-';
    }

    return keyPressOnlyNumber(e);
}
