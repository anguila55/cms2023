$(document).ready(function () {

    $('.first-button').on('click', function () {

      $('.animated-icon1').toggleClass('open');
    });
    $('.second-button').on('click', function () {
  
      $('.animated-icon2').toggleClass('open');
    });
    $('.third-button').on('click', function () {
  
      $('.animated-icon3').toggleClass('open');
    });
    

    $('.navbar-nav a').on('click', function () {

      $('.animated-icon1').removeClass('open');
    });

   /* 
    search_change();
    search_submit();
    selectInterest();
   */
$("#inscribirtemodal").modal("show");


// Search Bar & Toggle
$('#toggle-search').on('click', function() {
  $('#search-input').toggle('display: inline-block');
});
    /**Sobre El Congreso */  
    $('#dinamica-texto2 p').hide();
    $('#dinamica-texto3 p').hide();
    $('#dinamica-plenarias').click(function(){
      $('div[id^=dinamica-texto] p').hide();
      $('#dinamica-texto1 p').fadeIn();
    });
    $('#dinamica-talleres').click(function(){
        $('div[id^=dinamica-texto] p').hide();
        $('#dinamica-texto2 p').fadeIn();
    });
    $('#dinamica-maquinaria').click(function(){
      $('div[id^=dinamica-texto] p').hide();
      $('#dinamica-texto3 p').fadeIn();
  });
    /**Patrocinantes */
    $('#patrocinantes-texto2 p').hide();
    $('#patrocinantes-texto3 p').hide();
    $('#patrocinantes-texto4 p').hide();

    $('#taller-comercial').click(function(){
      $('div[id^=patrocinantes-texto] p').hide();
      $('#patrocinantes-texto1 p').fadeIn();
    });

    $('#preguntas-frecuentes').click(function(){
        $('div[id^=patrocinantes-texto] p').hide();
        $('#patrocinantes-texto2 p').fadeIn();
    });
    $('#reglamento').click(function(){
      $('div[id^=patrocinantes-texto] p').hide();
      $('#patrocinantes-texto3 p').fadeIn();
  });
  $('#web-app').click(function(){
    $('div[id^=patrocinantes-texto] p').hide();
    $('#patrocinantes-texto4 p').fadeIn();
});
    var navListItems = $('div.setup-panel div a'),
    allWells = $('.setup-content'),
    allNextBtn = $('.nextBtn'),
    allPrevBtn = $('.prevBtn');

    allWells.hide();

    navListItems.click(function (e) {
    e.preventDefault();
    var $target = $($(this).attr('href')),
            $item = $(this);

    if (!$item.hasClass('disabled')) {
        navListItems.removeClass('btn-primary').addClass('btn-default');
        $item.addClass('btn-primary');
        allWells.hide();
        $target.show();
        $target.find('input:eq(0)').focus();
    }
    });

    allPrevBtn.click(function(){
    var curStep = $(this).closest(".setup-content"),
        curStepBtn = curStep.attr("id"),
        prevStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().prev().children("a");

        prevStepWizard.removeAttr('disabled').trigger('click');
    });

    allNextBtn.click(function(){
    var curStep = $(this).closest(".setup-content"),
        curStepBtn = curStep.attr("id"),
        nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
        curInputs = curStep.find("input[type='text'],input[type='url']"),
        isValid = true;

    $(".form-group").removeClass("has-error");
    for(var i=0; i<curInputs.length; i++){
        if (!curInputs[i].validity.valid){
            isValid = false;
            $(curInputs[i]).closest(".form-group").addClass("has-error");
        }
    }

    if (isValid)

        nextStepWizard.removeAttr('disabled').trigger('click');
    });

    $('div.setup-panel div a.btn-primary').trigger('click');

    AOS.init();
    
    /* Search Bar & Toggle
    $('#toggle-search').on('click', function() {
      $('#search-input').toggle('display: inline-block');
    });
    
         //*** Chat ajuste ///**/
    
    $('.nav-item').click(function(){
      $(this).addClass('active')
         .siblings()
         .removeClass('active');
    });

    $('.link-no-mobile').click(function(){
         $('.link-no-mobile').removeClass('active');
         $(this).addClass('active');
         $(this).addClass('active');
    });

    $('.nav-link').click(function(){
      $('.nav-link').removeClass('activelink');
      $(this).addClass('activelink');
    });
    
   $('.panel-collapse').on('show.bs.collapse', function () {
    $(this).siblings('.panel-heading').addClass('active');
    });

    $('.panel-collapse').on('hide.bs.collapse', function () {
    $(this).siblings('.panel-heading').removeClass('active');
    });
    $('.navbar-nav>li>a').on('click', function(){
    $('.navbar-collapse').collapse('hide');
    });

    $('.toggle-inscripcion').on('click', function(){
    id = $(this).data('id');
    $(".inscripcion").hide();
    $('#'+id).show( "slow", function() {    
    });
   });
  $('#datepickerdate').datetimepicker({ 
    locale: 'es-es',
    language: 'es',
    uiLibrary: 'bootstrap4', 
    modal: true, 
    footer: true 
  });
  $(".next").click(function() {
      $('html,body').animate({
          scrollTop: $(".p2").offset().top},
          'slow');
  });
  $("input[type='checkbox']").change(function(){
    if($(this).is(":checked")){
        $(this).parent().addClass("active-check"); 
    }else{
        $(this).parent().removeClass("active-check");  
    }
  });

  $("#file-upload").change(function(){
    $("#file-name").text(this.files[0].name);
  });

  $('.mdb-select').materialSelect();

});

  $(window).scroll(function () {
    var scroll = $(window).scrollTop();
		if ($(this).scrollTop() > 20){
      $('.menu').addClass("fijo").fadeIn();
    } else {
      $('.menu').removeClass("fijo");
    }
  });

  if ("serviceWorker" in navigator) {
    navigator.serviceWorker
      .register("./sw.js")
      .then(reg => console.log("Registro de SW exitoso", reg))
      .catch(err => console.warn("Error al tratar de registrar el sw", err));
  }
  
