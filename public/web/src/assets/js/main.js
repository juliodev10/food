"use strict";

$(document).ready(function () {

  //======= START jQuery loadMoreResults ========

  $('.list1 .loadMore').loadMoreResults({
    button: {
      text: 'mais imagens'
    }
  });

  //======= END jQuery loadMoreResults ========


  //======= START Touch Swipe mobile menu ========

  //open left menu clicking the left menu icon
  $('.left_menu_icon').on('click', function (event) {
    event.preventDefault();
    toggleLeftNav(true);
    $("body").css({ 'overflow': 'hidden' });
  });

  //open right menu clicking the right menu icon
  $('.right_menu_icon').on('click', function (event) {
    event.preventDefault();
    toggleRightNav(true);
    $("body").css({ 'overflow': 'hidden' });
  });

  $('.cd-close-nav, .cd-overlay').on('click', function (event) {
    event.preventDefault();
    toggleLeftNav(false);
    toggleRightNav(false);
    $("body").css({ 'overflow': 'auto' });
  });
  //select a new section
  $('.cd-nav li').on('click', function () {

  });

  function toggleLeftNav(bool) {
    $('.left_menu, .cd-overlay').toggleClass('is-visible', bool);
    $('main').toggleClass('scale-down', bool);
  }

  function toggleRightNav(bool) {
    $('.right_menu, .cd-overlay').toggleClass('is-visible', bool);
    $('main').toggleClass('scale-down', bool);
  }

  //======= END Touch Swipe mobile menu ========


  //======= START Loading overlay ========

  $(window).on('load', function () {
    $('.loading-overlay').fadeOut(100);
  });

  //======= END Loading overlay ========


  //======= START Carousel slider ========

  $('.carousel').carousel({
    arrows: true
  })

  //======= END Carousel slider ========



  //======= START Menu filter ========
  $(document).on('click', '#todas', function () {
    /*Exibe todas os produtos*/
    $(".filter").show('1000');
    /** Removemos a classe active das outras opções da categoria*/
    $('.filter-button').closest('li').removeClass("active");
  });

  $(document).on('click', '.filter-button', function () {

    $(".filter-button").closest('li').removeClass("active");
    $(this).closest('li').addClass("active");

    var value = $(this).attr('data-filter');

    if (value === "todas") {
      $('.filter').show('1000');
    }
    else {
      $(".filter").not('.' + value).hide('3000');
      $('.filter').filter('.' + value).show('3000');

    }
  });

  //======= END Menu filter ========


  //======= START Search panel ========


  // Hide search panel
  function hideNavbarSearch() {
    $('.top_addr').fadeIn();
    $('#navbar_search').fadeOut();
  }

  // Show search panel
  $(document).on('click', '#search', function () {
    $('.top_addr').fadeOut();
    $('#navbar_search').fadeIn();
    $('#navbar_search input').focus();
  });

  // Trigger hideNavbarSearch() when click close button on search panel
  $(document).on('click', '#search_close', function () {
    hideNavbarSearch()
  })

  // Trigger hideNavbarSearch() when press ESC
  $(document).on('keydown', function (e) {
    if (e.keyCode === 27) { // ESC
      hideNavbarSearch()
    }
  });


  //======= END Search panel ========


  //======= START AOS Animate ========


  // Init AOS Animate On Scroll Library
  AOS.init({
    duration: 1200,
    startEvent: 'DOMContentLoaded',
    once: true,
  });


  //======= END AOS Animate ========



  //======= START Swipe Carousel slider ========

  // Add swipe mod to bootstrap carousel
  $(".carousel").swipe({

    swipe: function (event, direction, distance, duration, fingerCount, fingerData) {

      if (direction === 'left') $(this).carousel('next');
      if (direction === 'right') $(this).carousel('prev');

    },
    allowPageScroll: "vertical"

  });


  //======= END Swipe Carousel slider ========

  initContactMap();

});

//======= START Init OpenStreetMap ========

function initContactMap() {
  var mapElement = document.getElementById("googleMap");

  if (!mapElement || typeof L === 'undefined') {
    return;
  }

  var pratapolis = [-20.7444262, -46.8607973];

  var map = L.map(mapElement).setView(pratapolis, 13);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(map);

  L.marker(pratapolis)
    .addTo(map)
    .bindPopup('Gula Lanches do Ailton<br>Tv. Lemos, 86 - Pratápolis')
    .openPopup();
}

//======= END Init OpenStreetMap ========



//======= START Datepicker ========

$(document).ready(function () {
  $('#reserv_date').datepicker();
});

$(document).ready(function () {
  $('#reserv_time').datetimepicker({
    format: 'LT'
  });
});

//======= END Datepicker ========


//======= START Fancybox ========

jQuery(document).ready(function ($) {
  $('.fancybox')
    .fancybox({
      beforeShow: function () {
        if (this.title) {
          // New line
          this.title += '<br />';
        }
      },
      afterShow: function () {
      },
      helpers: {
        title: {
          type: 'inside'
        }, //<-- add a comma to separate the following option
        buttons: {} //<-- add this for buttons
      },
      closeBtn: true, // you will use the buttons now
      arrows: true
    });
});

//======= END Fancybox ========