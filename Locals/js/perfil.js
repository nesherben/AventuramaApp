// Agregar eventos onclick a los botones de la lista
var botones = document.querySelectorAll(".btnNino");
var url = document.querySelector('.baseUrl').value;

botones.forEach(btn => {
    btn.addEventListener('click', () => {
        $.ajax({
            url: url + '/perfil/getNino',
            method: 'POST',
            data: { 'id': btn.value },
            success: function (datos) {
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
    $("#centroEstudiosNE").val(nino['CENTRO_ESTUDIOS']);
    $("#observacionesNE").val(nino['OBSERVACIONES']);
    //deberiamos tener aqui para recibir los archivos de dni y ts
    $("#alergias_medicasNE").val(info['ALERGIA_MED']);
    $("#alergias_alimentariasNE").val(info['ALERGIA_ALI']);
    $("#medicacionNE").val(info['MED_ACTUAL']);
    $("#lesionNE").val(info['LESION']);
    $("#motivo_medicacionNE").val(info['MOTIVO_MED']);
    $("#discapacidadNE").val(info['DISCAPACIDAD']);
    $("#reacciones_alergicasNE").val(info['REAC_ALERGICA']);
    $("#vacunadoNE").val(info['VACUNADO']);
    $("#antitetanicaNE").val(info['ANTITETANICA']);
    $("#sabe_nadarNE").val(info['NATACION']);
    $("#aficionesNE").val(info['AFICIONES']);
    $("#observaciones_medNE").val(info['OBSERVACIONES']);

    document.querySelector("#inNombre").innerHTML = nino['NOMBRE'] + " " + nino['APELLIDOS'];
    document.querySelector("#inNacimiento").innerHTML = nino['FH_NACIMIENTO'];
    document.querySelector("#inCentro").innerHTML = nino['CENTRO_ESTUDIOS'];
    document.querySelector("#inObservaciones").innerHTML = nino['OBSERVACIONES'] + " " + info['OBSERVACIONES'];

    // Aquí puedes recibir los archivos de DNI y TS
    document.querySelector("#inAleMedica").innerHTML = info['ALERGIA_MED'];
    document.querySelector("#inAleAlim").innerHTML = info['ALERGIA_ALI'];
    document.querySelector("#inLesion").innerHTML = info['LESION'];
    document.querySelector("#inMedicina").innerHTML = info['MED_ACTUAL'];
    document.querySelector("#inDiscapacidad").innerHTML = info['DISCAPACIDAD'];
    document.querySelector("#inReacciones").innerHTML = info['REAC_ALERGICA'];
    document.querySelector("#inVacunas").innerHTML = info['VACUNADO'];
    document.querySelector("#inAntiteta").innerHTML = info['ANTITETANICA'];
    document.querySelector("#inNadar").innerHTML = info['NATACION'];
    document.querySelector("#inAficiones").innerHTML = info['AFICIONES'];

}

///////////////////////

//para los tutores

var botonesTutor = document.querySelectorAll(".btnTutor");

botonesTutor.forEach(btn => {
    btn.addEventListener('click', () => {
        $.ajax({
            url: url + '/perfil/getTutor',
            method: 'POST',
            data: { 'idTutor': btn.value },
            success: function (datos) {
                datosToFormTutor(JSON.parse(datos));
            }

        });
    })

}
);

function datosToFormTutor(datos) {
    $("#Tnombre").val(datos["NOMBRE"]);
    $("#Tapellidos").val(datos['APELLIDOS']);
    $("#Temail").val(datos["EMAIL"]);
    $("#Ttelefono").val(datos['NUM_TLFN']);
    $("#Tdni").val(datos['DNI']);
    $("#Tdireccion").val(datos['DIRECCION']);
    $("#Tdireccion2").val(datos['DIRECCION2']);
    $("#Tcp").val(datos['CP']);
    $("#Tlocalidad").val(datos['LOCALIDAD']);
    $("#Tprovincia").val(datos['PROVINCIA']);
    $("#Tobservaciones").val(datos['OBSERVACIONES']);

    document.querySelector("#it1Nombre").innerHTML = datos["NOMBRE"] + " " + datos['APELLIDOS'];
    document.querySelector("#it1Email").innerHTML = datos["EMAIL"];
    document.querySelector("#it1Telefono").innerHTML = datos['NUM_TLFN'];
    document.querySelector("#it1Dni").innerHTML = datos['DNI'];
    document.querySelector("#it1Direccion1").innerHTML = datos['DIRECCION'];
    document.querySelector("#it1Direccion2").innerHTML = datos['DIRECCION2'];
    document.querySelector("#it1CP").innerHTML = datos['CP'];
    document.querySelector("#it1Localidad").innerHTML = datos['LOCALIDAD'];
    document.querySelector("#it1Provincia").innerHTML = datos['PROVINCIA'];
}