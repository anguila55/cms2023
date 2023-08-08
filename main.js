$(function () {
  $("#wizard").steps({
    headerTag: "h2",
    bodyTag: "section",
    transitionEffect: "fade",
    enableAllSteps: true,
    transitionEffectSpeed: 500,
    labels: {
      finish: "Enviar/Send",
      next: "Siguiente/Next",
      previous: "Atras/Back",
    },

    onFinished: function (event, currentIndex) {
      $(".icons-tab-steps").hide();
      $("#lbcamposesp").hide();
      $("#lbcamposing").hide();

      var pernombre = $("#pernombre").val();
      var perapelli = $("#perapelli").val();
      var percpf = $("#percpf").val();
      var percompan = $("#percompan").val();
      var pertipo = 2;
      var pertelefo = $("#pertelefo").val();
      var percargo  = $("#percargo").val();
      var perdirecc = $("#perdirecc").val();
      var perestado = $("#perestado").val();
      var perciudad = $("#perciudad").val();
      var perciudad = $("#perciudad").val();
      var percodpos = $("#percodpos").val();
      var paicodigo = $("#paicodigo").val();
      var perusuacc = $("#perusuacc").val();
      var perpasacc = $("#perpasacc").val();
      var peridioma = $("#peridioma").val();
	  var perinfact = ($('#chkinfact').is(':checked'))? 'S': 'N'; //Check de Informacion de Actividades
	  var perinfpar = ($('#chkinfpar').is(':checked'))? 'S': 'N'; //Check de Informacion de Participantes

     // //var cmbinteres = $("#cmbinteres").val();
      //var interes = "";
      //$.each(cmbinteres, function (i, val) {
      //  interes += val + ",";
      //});
      //interes += "0";

      var errcode = 0;

      if (!$("#chkinfter").prop("checked")) {
        errcode = 2;
        toastr.error("Por favor acepte los Términos y condiciones para continuar.", "ERROR!");
      }

      //Verificamos que los campos no esten en blanco
      if ($("#pernombre").val() == "") {
        errcode = 2;
        toastr.error("Complete el campo requerido Nombre*", "ERROR!");
      }
      if ($("#perapelli").val() == "") {
        errcode = 2;
        toastr.error("Complete el campo requerido Apellido*", "ERROR!");
      }
      if ($("#percpf").val() == "") {
        errcode = 2;
        toastr.error("Complete el campo requerido DNI*", "ERROR!");
      }
       if($("#percompan").val() == ""){
      	errcode=2;
        toastr.error("Complete el campo requerido Empresa*", "ERROR!");
       }
       if($("#percargo").val() == ""){
      	errcode=2;
        toastr.error("Complete el campo requerido Empresa*", "ERROR!");
       }
       if($("#peridioma").val() == ""){
      	errcode=2;
        toastr.error("Prueba*", "ERROR!");
       }

     // if ($("#pertipo").val() == "") {
       // errcode = 2;
        //toastr.error("Complete el campo requerido Categoria*", "ERROR!");
     // }
      // if($("#perclase").val().length == 0){
      // 	errcode=2;

      // }
      // if($("#perrubcod").val().length == 0){
      // 	errcode=2;

      // }
      // if($("#percorreo").val().length == 0){
      // 	errcode=2;

      // }
      // if($("#pertelefo").val().length == 0){
      // 	errcode=2;

      // }
      // if($("#perurlweb").val().length == 0){
      // 	errcode=2;

      // }
      if ($("#perestado").val() == "") {
        errcode = 2;
        toastr.error("Complete el campo requerido Provincia*", "ERROR!");
      }
      if ($("#paicodigo").val() == "") {
        errcode = 2;
        toastr.error("Complete el campo requerido País*", "ERROR!");
      }
      if ($("#percodpos").val() == "") {
        errcode = 2;
        toastr.error("Complete el campo requerido Código Postal*", "ERROR!");
      }
      if ($("#perusuacc").val() == "") {
        errcode = 2;
        toastr.error("Complete el campo requerido Usuario*", "ERROR!");
      }
      if ($("#perpasacc").val() == "") {
        errcode = 2;
        toastr.error("Complete el campo requerido Contraseña*", "ERROR!");
      }

      const valmail = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

      if (valmail.test(perusuacc)) {
      } else {
        errcode = 2;
        toastr.error(
          "Verifique el Usuario ingresado, debe ser un correo electronico",
          "ERROR"
        );
      }
	  
	  var imgload = $('<img src="assets-nuevodisenio/img/registro/loading.gif">');
	  imgload.appendTo($("a[href$='finish']").parent());
    $("a[href$='finish']").hide();
    $("a[href$='previous']").hide();
	  
      if (errcode == 0) {
        $(".loading").attr("src", "assets-nuevodisenio/img/registro/loading.gif");
        var data = {
          pernombre: pernombre,
          // "pernombre":pernombre,
          perapelli: perapelli,
          percpf: percpf,
          percompan: percompan,
          perciudad: perciudad,
          percargo:percargo,
          pertipo: pertipo,
          // "perclase":perclase,
          // "perrubcod":perrubcod,
          // "percorreo":percorreo,
          pertelefo: pertelefo,

         // pertwi: pertwi,
         // perfac: perfac,
         // interes: interes,
          // "perurlweb":perurlweb,
          perdirecc:perdirecc,
         // perciudad:perciudad,
          perestado:perestado,
          percodpos:percodpos,
          paicodigo:paicodigo,
          perdioma:peridioma,
          // "perparnom1":perparnom1,
          // "perparape1":perparape1,
          // "perparcarg1":perparcarg1,
          // "perparnom2":perparnom2,
          // "perparape2":perparape2,
          // "perparcarg2":perparcarg2,
          // "perparnom3":perparnom3,
          // "perparape3":perparape3,
          // "perparcarg3":perparcarg3,

          perusuacc: perusuacc,
          perpasacc: perpasacc,
		  perinfact: perinfact,
		  perinfpar: perinfpar
          // "percoment":percoment
        };

        $("a[href$='finish']").hide();
        $("a[href$='previous']").hide();

        $.ajax({
          type: "POST",
          url: "registersend.php",
          data: data,
        }).done(function (rsp) {
          data = $.parseJSON(rsp);
          if (data.errcod == 0) {
            //toastr.success(data.errmsg, 'ELIMINAR');
            toastr.success(
              "Se ha enviado un mail a su casilla de correo.",
              "CONFIRMAR"
            );
            // alert('Se ha enviado un mail a su casilla de correo.');
            setTimeout(function () {
              window.location = "registermail";
            }, 3000);
          } else {
            //toastr.error(data.errmsg, 'ELIMINAR');
            //errmsg="usuario ya registrado"

            toastr.error(data.errmsg, "ERROR!");
            //$(".loading").attr("src", "assets-nuevodisenio/img/registro/lef5.png");
			imgload.remove();
            $(".icons-tab-steps").show();
            $("#lbcamposesp").show();
            $("#lbcamposing").show();
            $("a[href$='finish']").show();
            $("a[href$='previous']").show();
          }
        });
      } else {
        toastr.error(
          "Porfavor verifique los campos requeridos, son obligatorios para poder registrarse",
          "ERROR!"
        );
        $(".icons-tab-steps").show();
        $("#lbcamposesp").show();
        $("#lbcamposing").show();
        $("a[href$='finish']").show();
        $("a[href$='previous']").show();
		imgload.remove();
      }
    },
  });

  $(".wizard > .steps li a").click(function () {
    $(this).parent().addClass("checked");
    $(this).parent().prevAll().addClass("checked");
    $(this).parent().nextAll().removeClass("checked");
  });
  // Custome Jquery Step Button
  $(".forward").click(function () {
    $("#wizard").steps("next");
  });
  $(".backward").click(function () {
    $("#wizard").steps("previous");
  });
  // Select Dropdown
  $("html").click(function () {
    $(".select .dropdown").hide();
  });
  $(".select").click(function (event) {
    event.stopPropagation();
  });
  $(".select .select-control").click(function () {
    $(this).parent().next().toggle();
  });
  $(".select .dropdown li").click(function () {
    $(this).parent().toggle();
    var text = $(this).attr("rel");
    $(this).parent().prev().find("div").text(text);
  });
});
