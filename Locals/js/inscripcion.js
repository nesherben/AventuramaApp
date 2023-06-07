// Agregar eventos onclick a los botones de la lista
var botones = document.querySelectorAll(".btnNino");
var url = document.querySelector('.baseUrl').value;

botones.forEach(btn => {    
    btn.addEventListener('click', () => {
        $.ajax({
            url: url+'/inscripcion/getNino',
            method: 'POST',
            data: {'id': btn.value},
            success: function(datos) {
                datosToForm(JSON.parse(datos));
            }
            
        });
    })
    
});


function datosToForm(datos) {

    var info = datos.info;
    var nino = datos.nino;

    $("#idNino").val(nino['ID_NINO']);
    $("#nombreNE").val(nino['NOMBRE']);
    $("#apellidosNE").val(nino['APELLIDOS']);
    $("#fechaNacimientoNE").val(nino['FH_NACIMIENTO']);
    $("#dniNE").val(nino['DNI']);
    $("#centroEstudiosNE").val(nino['CENTRO_ESTUDIOS']);
    $("#observacionesNE").val(nino['OBSERVACIONES']);

    $("#alergias_medicasNE").val(info['ALERGIA_MED']);
    $("#alergias_alimentariasNE").val(info['ALERGIA_ALI']);
    $("#medicacionNE").val(info['MED_ACTUAL']);
    $("#motivo_medicacionNE").val(info['MOTIVO_MED']);
    $("#discapacidadNE").val(info['DISCAPACIDAD']);
    $("#reacciones_alergicasNE").val(info['REAC_ALERGICA']);
    $("#vacunadoNE").val(info['VACUNADO']);
    $("antitetanicaNE").val(info['ANTITETANICA']);
    $("#sabe_nadarNE").val(info['NATACION']);
    $("#aficionesNE").val(info['AFICIONES']);
    $("#observaciones_medNE").val(info['OBSERVACIONES']);
}

/////// controlar que el boton de inscripcion no salga hasta que no se seleccionen niños

document.querySelectorAll(".checkNino").forEach(check => {

    check.addEventListener('click', () => {

        var btn = document.querySelector("#submitbtn");
        var checks = document.querySelectorAll(".checkNino");
        var checked = false;

        checks.forEach(check => {
            if (check.checked) {
                checked = true;
            }
        });

        if (checked) {
            btn.disabled = false;
        } else {
            btn.disabled = true;
        }

    });


});