// Agregar eventos onclick a los botones de la lista

var url = document.querySelector('.baseUrl').value;
var params = new URLSearchParams(new URL(window.location.href).search);


document.getElementsByName('turnoc')[0].value = params.get('turnoc');
document.getElementsByName('turnor')[0].value = params.get('turnor');
document.getElementsByName('actividadc')[0].value = params.get('actividadc');
document.getElementsByName('actividadr')[0].value = params.get('actividadr');
document.getElementsByName('ninor')[0].value = params.get('ninor');
document.getElementsByName('ninoc')[0].value = params.get('ninoc');
document.getElementsByName('estador')[0].value = params.get('estador');


//////////////////

var botones = document.querySelectorAll(".btnActividad");
botones.forEach(btn => {
    btn.addEventListener('click', () => {
        $.ajax({
            url: url + 'adminpage/getActividad',
            method: 'POST',
            data: { 'codigo': btn.value },
            success: function (datos) {
                datosToForm(JSON.parse(datos));
            }
        });
    })

});


function datosToForm(datos) {
    $("#codigoActividadAE").val(datos['CD_ACTIVIDAD']);
    $("#codigoActividadE").val(datos['CD_ACTIVIDAD']);
    $("#nombreActividadE").val(datos['NM_ACTIVIDAD']);
    $("#desActividadE").val(datos['DS_ACTIVIDAD']);
    $("#catActividadE").val(datos['CT_ACTIVIDAD']);
    $("#imagenActividadE").val(datos['URL_IMAGEN']);

    let turnos_array = datos['TURNOS'].split(',');
    let opciones = document.getElementById("turnosActividadE")?.options;
    if(opciones)
    for (let i = 0; i < opciones.length; i++) {
        if (turnos_array.indexOf(opciones[i].value) !== -1) {
            opciones[i].selected = true;
        }
    }
}
/////////////////
var botones2 = document.querySelectorAll(".btnEstados");
botones2.forEach(btn => {
    btn.addEventListener('click', () => {
        $.ajax({
            url: url + 'adminpage/getEstadosActividad',
            method: 'POST',
            data: { 'codigo': btn.value },
            success: function (datos) {

                datosToModal(JSON.parse(datos));
            }
        });
    })

});

function datosToModal(datos) {
    let turnosEstadoActividad = document.getElementById("turnosEstadoActividad");
    turnosEstadoActividad.innerHTML = "";

    datos.forEach(elem => {

        turnosEstadoActividad.innerHTML += `
        <div>
        <form action="${url}adminpage/setEstadosActividad" class="d-flex justify-content-between" method="POST">
        
            <label value="${elem.TURNO}">${elem.DS_TURNO ?? "Turno único"}</label>
            <label value="${elem.ESTADO}">${elem.DS_ESTADO}</label>  
            <input hidden name="turno" value="${elem.TURNO}">          
            ${elem.ESTADO != 5 ? `<button class="btn btn-sm border" type="submit" name="codigo" value="${elem.CD_ACTIVIDAD}">Continuar</button>` : ""}
            
        </form>
        </div>
        `;
    })

}

////////////////////

var botones3 = document.querySelectorAll(".btnInfos");
botones3.forEach(btn => {
    btn.addEventListener('click', () => {
        $.ajax({
            url: url + 'adminpage/getAllInfo',
            method: 'POST',
            data: { 'id_nino': btn.value },
            success: function (datos) {
                datosToModalInfo(JSON.parse(datos));
            }
        });
    })

});

function datosToModalInfo(datos) {


    document.querySelector("#iuNombre").innerHTML = datos['padre']["nombre"] + " " + datos['padre']['apellidos'];
    document.querySelector("#iuEmail").innerHTML = datos['padre']["email"];
    document.querySelector("#iuTelefono").innerHTML = datos['padre']['telefono'];
    document.querySelector("#iuDni").innerHTML = datos['padre']['dni'];
    document.querySelector("#iuDireccion1").innerHTML = datos['padre']['direccion'];
    document.querySelector("#iuDireccion2").innerHTML = datos['padre']['direccion2'];
    document.querySelector("#iuCP").innerHTML = datos['padre']['cp'];
    document.querySelector("#iuLocalidad").innerHTML = datos['padre']['localidad'];
    document.querySelector("#iuProvincia").innerHTML = datos['padre']['provincia'];
    /////////

    document.querySelector("#inNombre").innerHTML = datos['nino']["NOMBRE"] + " " + datos['nino']['APELLIDOS'];
    document.querySelector("#inCentro").innerHTML = datos['nino']["CENTRO_ESTUDIOS"];
    document.querySelector("#inNacimiento").innerHTML = datos['nino']["FH_NACIMIENTO"];
    document.querySelector("#inAleMedica").innerHTML = datos['ninoSan']["ALERGIA_MED"];
    document.querySelector("#inAleAlim").innerHTML = datos['ninoSan']["ALERGIA_ALI"];
    document.querySelector("#inLesion").innerHTML = datos['ninoSan']["LESION"];
    document.querySelector("#inMedicina").innerHTML = datos['ninoSan']["MED_ACTUAL"];
    document.querySelector("#inDiscapacidad").innerHTML = datos['ninoSan']["DISCAPACIDAD"];
    document.querySelector("#inReacciones").innerHTML = datos['ninoSan']["REAC_ALERGICA"];
    document.querySelector("#inVacunas").innerHTML = datos['ninoSan']["VACUNADO"];
    document.querySelector("#inAntiteta").innerHTML = datos['ninoSan']["ANTITETANICA"];
    document.querySelector("#inNadar").innerHTML = datos['ninoSan']["NATACION"];
    document.querySelector("#inAficiones").innerHTML = datos['ninoSan']["AFICIONES"];
    document.querySelector("#inObservaciones").innerHTML = datos['nino']["OBSERVACIONES"] + " " + datos['ninoSan']['OBSERVACIONES'];


    let primertutor = datos['tutor'][0] ?? "";

    if (primertutor) {
        //todo: aqui añadir los datos de los tutores   
        document.querySelector("#it1Nombre").innerHTML = primertutor["NOMBRE"] + " " + primertutor['APELLIDOS'];
        document.querySelector("#it1Email").innerHTML = primertutor["EMAIL"];
        document.querySelector("#it1Telefono").innerHTML = primertutor['NUM_TLFN'];
        document.querySelector("#it1Dni").innerHTML = primertutor['DNI'];
        document.querySelector("#it1Direccion1").innerHTML = primertutor['DIRECCION'];
        document.querySelector("#it1Direccion2").innerHTML = primertutor['DIRECCION2'];
        document.querySelector("#it1CP").innerHTML = primertutor['CP'];
        document.querySelector("#it1Localidad").innerHTML = primertutor['LOCALIDAD'];
        document.querySelector("#it1Provincia").innerHTML = primertutor['PROVINCIA'];
        /////////
    }
}

//////////////////todo:
//es necesario cargar las tablas por ajax