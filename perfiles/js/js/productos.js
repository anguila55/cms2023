//Oculto por defecto el boton agregar
$("#confirmar-producto").hide();
$("#cancelar-producto").hide();
//EXPREG
let SPONSOR_ID = $("#sponsor_id").val();

//Cantidad de productos maximos
var cantidadProductosMaximos = 3;

//Cuantos productos fue agregando
var acumuladorProductos = 1;

//Si ya cofirmio el producto 0 sino 1
var confirmProduct = 1;

//Boton agregar producto
$("#agregar-producto").click((e) => {
  //
  e.preventDefault();

  if (acumuladorProductos <= cantidadProductosMaximos) {
    if (confirmProduct != 0) {
      confirmProduct = 0;
      $("#confirmar-producto").show();
      $("#cancelar-producto").show();

      agregarNuevoProducto();
    } else {
      toastr.error("Confirme el producto actual");
    }
  } else {
    toastr.error("Cantidad maxima de productos");
  }
});

$("#cancelar-producto").click((e) => {
  //
  e.preventDefault();

  $("#confirmar-producto").hide();
  $("#lista-productos").html("");
  $("#cancelar-producto").hide();
  confirmProduct = 1;
});

//Boton confirmar
$("#confirmar-producto").click((e) => {
  e.preventDefault();

  var formData = new FormData();

  //Valido Datos
  formData.append("prodnombre", $("#prodnombre").val());
  formData.append("expreg", SPONSOR_ID);
  if (typeof $("#prodimg")[0].files[0] !== "undefined") {
    formData.append("prodimg", $("#prodimg")[0].files[0]);
  }
  if (typeof $("#prodfolleto")[0].files[0] !== "undefined") {
    formData.append("prodfolleto", $("#prodfolleto")[0].files[0]);
  }
  guardarProducto(formData);
});

//Funciones

//Esqueleto vacio del producto
function agregarNuevoProducto() {
  $("#lista-productos").append(
    `<h6 class="card-title mb-2 mt-3">Producto</h6>
        <input placeholder="Titulo" type="text" id="prodnombre"
            class="form-control mb-3" value="">
        <div class="col-md-12">
            <label for="exptelefo">Imagen:</label>
     
            <img id="input-img"  class="media-object d-flex mr-3" src="../app-assets/img/pages/sativa.png" style="width: 80px; height: 80px;" onclick="$('#prodimg').trigger('click');">
            <input class="d-none" type="file" name="prodimg" id="prodimg" onchange="changeImage(this.value);">
            <!-- <a onclick="delCampo({expreg},'prodimg')"
                class="btn btn-danger mr-1 btn-fab">
                <i class="icon-close"></i>
            </a> -->

        </div>
        <div class="col-md-12">

            <label for="prodfolleto">Folleto: </label>
            <img id="input-folleto"  class="media-object d-flex mr-3" src="../app-assets/img/pages/sativa.png" style="width: 80px; height: 80px;" onclick="$('#prodfolleto').trigger('click');">
            <input class="d-none" type="file" name="prodfolleto" id="prodfolleto" onchange="changeFolleto()">
           

        </div>
    `
  );
}

function changeImage(input) {
  $("#input-img").attr("src", input);
}

function changeFolleto() {
  $("#input-folleto").hide();
}

//Esqueleto cargardo por el usuario
function productoConfirmado(valNombre, valImagen) {
  $("#nuevos-productos").append(`
    <h4 class="card-title text-center mt-3 mb-4">${valNombre}</h4>

    <div class="col-md-12 text-center">


        <img src="../expimg/${expreg}/${valImagen}" alt="">

    </div>
    `);
}

//Save producto
function guardarProducto(formData) {
  $.ajax({
    type: "POST",
    url: "grbproducto.php",
    dataType: "html",
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
  }).done(function (rsp) {
    data = $.parseJSON(rsp);

    if (data.errcod == 0) {
      toastr.success(data.errmsg, "GUARDAR");
      $("#lista-productos").html("");
      $("#confirmar-producto").hide();
      $("#cancelar-producto").hide();

      confirmProduct = 1;
      productoConfirmado(formData.get("prodnombre"), data.imgname);
      acumuladorProductos++;
    } else {
      toastr.error(data.errmsg, "GUARDAR");
    }
  });
}
