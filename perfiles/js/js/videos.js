//Oculto por defecto el boton agregar

$("#confirmar-video").hide();
$("#cancelar-video").hide();
//EXPREG

expreg = $("#expreg").val();

//Cantidad maxima de elementos
var cantidadVideosMaximos = $("#catvid").val();

//Cuantos elementos fue agregando
var acumuladorVideos = 1;

//Si ya cofirmio el elemento 0 sino 1
var confirmVideo = 1;

$("#cancelar-video").click((e) => {
  //
  e.preventDefault();

  $("#confirmar-video").hide();
  $("#lista-videos").html("");
  $("#cancelar-video").hide();
  confirmVideo = 1;
});

//Boton agregar producto
$("#agregar-video").click((e) => {
  //
  e.preventDefault();

  if (acumuladorVideos <= cantidadVideosMaximos) {
    if (confirmVideo != 0) {
      confirmVideo = 0;
      $("#confirmar-video").show();
      $("#cancelar-video").show();

      agregarNuevoVideo();
    } else {
      toastr.error("Confirme el texto actual");
    }
  } else {
    toastr.error("Cantidad maxima de textos");
  }
});

//Boton confirmar
$("#confirmar-video").click((e) => {
  e.preventDefault();

  var formData = new FormData();

  //Valido Datos
  formData.append("vidnombre", $("#vidnombre").val());
  formData.append("vidurl", $("#vidurl").val());
  formData.append("vidurlid", getIdUrl($("#vidurl").val()));
  formData.append("expreg", expreg);

  guardarVideo(formData);
});

//Funciones

//Esqueleto vacio del producto
function agregarNuevoVideo() {
  $("#lista-videos").append(`		<div class="col-md-12">
    <h6 class="card-title">Video</h6>
    <label for="pernombre">Titulo:</label>
    <input type="text" id="vidnombre" value="" name=""
        class="form-control mb-3" value="">
    <label for="exptelefo">Link Youtube: </label>
    <input type="text" id="vidurl"  class="form-control mb-3"
        value="">
</div>`);
}

//Esqueleto cargardo por el usuario
function videoConfirmado(valNombre, valUrl) {
  $("#videos-nuevos").append(`
    <h6>Titulo: ${valNombre}</h6>
    <iframe width="560" height="315" src="https://www.youtube.com/embed/${valUrl}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>">
`);
}

//Save producto
function guardarVideo(formData) {
  $.ajax({
    type: "POST",
    url: "grbvideo.php",
    dataType: "html",
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
  }).done(function (rsp) {
    data = $.parseJSON(rsp);

    if (data.errcod == 0) {
      toastr.success(data.errmsg, "GUARDAR");
      $("#lista-videos").html("");
      $("#confirmar-video").hide();
      alert();
      confirmVideo = 1;
      videoConfirmado(formData.get("vidnombre"), formData.get("vidurlid"));
      acumuladorVideos++;
    } else {
      toastr.error(data.errmsg, "GUARDAR");
    }
  });
}

function getIdUrl(url) {
  let regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
  let match = url.match(regExp);

  return match && match[2].length === 11 ? match[2] : null;
}
