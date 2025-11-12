// Variables globales para almacenar la información
var globalIP = '';
var globalCity = '';
var globalRegion = '';
var globalCountry = '';
var globalLocation = '';
var globalOrg = '';

function obtenerInformacionIP() {
    $.get("https://api.ipify.org?format=json", function(data) {
        var ip = data.ip;

        // Ahora, usa ipinfo.io para obtener información sobre esa IP
        $.get("https://ipinfo.io/" + ip + "/json?token=327e7a51328f04", function(info) {
            // Actualiza las variables globales
            globalIP = info.ip;
            globalCity = info.city;
            globalRegion = info.region;
            globalCountry = info.country;
            globalLocation = info.loc;
            globalOrg = info.org;
        });
    });
}



$(document).ready(function () {

    $(".traducir").on("click", function () {
        obtenerInformacionIP();
        // Obtener el valor del textarea
        var texto = $("#texto").val();
        var idioma = $("#idioma").val();
        var caso = 'traductor';

        if (!texto) {
            alert("Por favor ingrese una palabra para traducir.");
            return;
        }

        // Hacer la petición AJAX al servidor
        $.ajax({
            url: "index.php", // Cambia por la URL a la que harás la petición
            method: "POST",
            data: {
                palabra: texto,
                caso: caso,
                idioma: idioma,
                ip: globalIP,
                ciudad: globalCity,
                region: globalRegion
            },
            success: function (response) {
                console.log(response);

                // El servidor responde con { traduccion: "palabraTraducida" }
                $("#traducido").val(response); // Mostrar la respuesta en el segundo textarea
            },
            error: function () {
                alert("Hubo un error al traducir la palabra.");
            }
        });
    });

});

$(document).ready(function () {
    $('#addWordBtn').click(function () {
        var word_es = $('#word_es').val();
        var word_quiche = $('#word_quiche').val();
        var caso = 'agregar';
        if (!word_es && !word_quiche) {
            alert('Debe de ingresar los 2 valores');
            return;
        }
        $.ajax({
            type: "POST",
            url: "index.php",
            data: {
                word_es: word_es,
                word_quiche: word_quiche,
                caso: caso
            },
            success: function (response) {
                console.log(response);
                if (response == 'Listo') {
                    $('#close_modal').click();
                    alert('Realizado');
                } else {
                    $('#close_modal').click();
                    alert('No se logo subir');
                }
            },
            error: function (error) {
                alert('Hubo un error al agregar las palabras.');
            }
        });
    });
});

