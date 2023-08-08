//Oculto por defecto el boton agregar

$("#confirmar-imagen").hide();
$("#cancelar-imagen").hide();

//EXPREG
expreg = $("#expreg").val();

//alert(expreg);

//Cantidad maxima de elementos
var cantidadImagenesMaximos = $("#catimg").val();

//Cuantos elementos fue agregando
var acumuladorImagenes = 1;

//Si ya cofirmio el elemento 0 sino 1
var confirmarImagen = 1;

//Boton agregar producto
$("#agregar-imagen").click((e) => {
  //
  e.preventDefault();

  if (acumuladorImagenes <= cantidadImagenesMaximos) {
    if (confirmarImagen != 0) {
      confirmarImagen = 0;
      $("#confirmar-imagen").show();
      $("#cancelar-imagen").show();

      agregarNuevaImagen();
    } else {
      toastr.error("Confirme la imagen actual");
    }
  } else {
    toastr.error("Cantidad maxima de imagenes");
  }
});

$("#cancelar-imagen").click((e) => {
  //
  e.preventDefault();

  $("#confirmar-imagen").hide();
  $("#lista-imagenes").html("");
  $("#cancelar-imagen").hide();
  confirmarImagen = 1;
});

//Boton confirmar
$("#confirmar-imagen").click((e) => {
  e.preventDefault();

  var formData = new FormData();

  //Valido Datos

  formData.append("expreg", expreg);
  formData.append("catimg", cantidadImagenesMaximos);
  if (typeof $("#expimg")[0].files[0] !== "undefined") {
    formData.append("expimg", $("#expimg")[0].files[0]);
  }

  guardarImagen(formData);
});

//Funciones

//Esqueleto vacio del producto
function agregarNuevaImagen() {
  $("#lista-imagenes").append(`<div class="col-md-12">
    <h6 class="card-title">Imagen</h6>

    <label>Imagen</label>
    <img   class="media-object d-flex mr-3" src="../app-assets/img/pages/sativa.png" style="width: 80px; height: 80px;" onclick="$('#expimg').trigger('click');">
    <input class="d-none" type="file" name="expimg" id="expimg">

</div>`);
}
//Esqueleto cargardo por el usuario
function imagenConfirmada(valImg) {
  $("#imagenes-nuevas").append(`<div class="col-md-12">
    <h6 class="card-title">Imagen</h6>

  
    <img class="img-fluid p-5 w-50" src="../expimg/${expreg}/${valImg}" alt="" width="100%">

</div>`);
}
//Save producto
function guardarImagen(formData) {
  $.ajax({
    type: "POST",
    url: "grbimagen.php",
    dataType: "html",
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
  }).done(function (rsp) {
    data = $.parseJSON(rsp);

    if (data.errcod == 0) {
      toastr.success(data.errmsg, "GUARDAR");
      $("#lista-imagenes").html("");
      $("#confirmar-imagen").hide();
      $("#cancelar-imagen").hide();

      imagenConfirmada(data.imgname);
      confirmarImagen = 1;
      acumuladorImagenes++;
    } else {
      toastr.error(data.errmsg, "GUARDAR");
    }
  });
}
