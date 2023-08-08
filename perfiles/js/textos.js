//Oculto por defecto el boton agregar

$("#confirmar-texto").hide();
$("#cancelar-texto").hide();

//EXPREG
let expreg = $("#expreg").val();

//Cantidad maxima de elementos
var cantidadTextosMaximos = $("#cattxt").val();

//Cuantos elementos fue agregando
var acumuladorTextos = 1;

//Si ya cofirmio el elemento 0 sino 1
var confirmTexto = 1;

//Boton agregar producto
$("#agregar-texto").click((e) => {
  //
  e.preventDefault();

  if (acumuladorTextos <= cantidadTextosMaximos) {
    if (confirmTexto != 0) {
      confirmTexto = 0;
      $("#confirmar-texto").show();
      $("#cancelar-texto").show();

      agregarNuevoTexto();
    } else {
      toastr.error("Confirme el texto actual");
    }
  } else {
    toastr.error("Cantidad maxima de textos");
  }
});

$("#cancelar-texto").click((e) => {
  //
  e.preventDefault();

  $("#confirmar-texto").hide();
  $("#lista-textos").html("");
  $("#cancelar-texto").hide();
  confirmTexto = 1;
});

//Boton confirmar
$("#confirmar-texto").click((e) => {
  e.preventDefault();

  var formData = new FormData();

  //Valido Datos
  formData.append("txtnombre", $("#txtnombre").val());
  formData.append("txtdescri", $("#txtdescri").val());
  formData.append("expreg", expreg);
  formData.append("cattxt", cantidadTextosMaximos);

  if ($('#txtdescri').val().length > 1000) {
    toastr.error('Revisar Campos', 'Cuadro de texto demasiado largo');
  } else {
    guardarTexto(formData);
  }
});

//Funciones

//Esqueleto vacio del producto
function agregarNuevoTexto() {
  $("#lista-textos").append(`	<div class="col-md-12 mb-3">
    <h6 class="card-title">Cuadro de texto</h6>

    <label for="pernombre">Titulo: </label>
    <input value="" type="text" id="txtnombre" name=""
        class="form-control mb-3" value="">
    <label for="pernombre">Contenido (1000 caracteres máximo): </label>
    <textarea class="form-control" rows="8" id="txtdescri" name=""
        class="form-control"> </textarea>
</div>`);
}

//Esqueleto cargardo por el usuario
function textoConfirmado(valNombre, valDescri) {
  $("#textos-nuevos").append(`<div class="col-md-12 mb-3">
    <h6 class="card-title">Cuadro de texto </h6>

    <div class="card texto1 d-{display-texto1}">
    <div class="card-header">
        <div class="card-title-wrap bar-info">
            <div class="card-title"><strong>${valNombre}</strong></div>
        </div>
    </div>
    <div class="card-body">
        <div class="card-block">
            <div class="mb-3">
                <span class="display-block overflow-hidden">${valDescri}</span>
            </div>

        </div>

    </div>
</div>
</div>
    `);
}

//Save producto
function guardarTexto(formData) {
  $.ajax({
    type: "POST",
    url: "grbtexto.php",
    dataType: "html",
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
  }).done(function (rsp) {
    data = $.parseJSON(rsp);

    if (data.errcod == 0) {
      toastr.success(data.errmsg, "GUARDAR");
      $("#lista-textos").html("");
      $("#confirmar-texto").hide();
      $("#cancelar-texto").hide();

      confirmTexto = 1;
      textoConfirmado(formData.get("txtnombre"), formData.get("txtdescri"));
      acumuladorTextos++;
    } else {
      toastr.error(data.errmsg, "GUARDAR");
    }
  });
}
