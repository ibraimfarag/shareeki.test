(function () {
   'use strict'
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
   var forms = document.querySelectorAll('.needs-validation')

   // Loop over them and prevent submission
   Array.prototype.slice.call(forms)
      .forEach(function (form) {
         form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
               event.preventDefault()
               event.stopPropagation()
            }

            form.classList.add('was-validated')
         }, false)
      });


   // make the dropdown clickable in phone screen
   function toggleNavbarMethod() {
      if ($(window).width() > 768) {
         $('.navbar .dropdown').on('mouseover', function () {
            $('.dropdown-toggle', this).trigger('click');
         }).on('mouseout', function () {
            $('.dropdown-toggle', this).trigger('click').blur();
         });
      } else {
         $('.navbar .dropdown').off('mouseover').off('mouseout');
      }
   }
   toggleNavbarMethod();
   $(window).on('resize', toggleNavbarMethod);


   // Remove all check boxs
   $('#checkboxs-wrap  .clear-checkboxs').on('click', function () {

      $('#checkboxs-wrap .btn-check[type=checkbox]').each(function () {
         // this.checked = false;
         $(this).prop('checked', false);
      });
      $(this).prop('checked', false);
   })


})()



var TxtRotate = function (el, toRotate, period) {
   this.toRotate = toRotate;
   this.el = el;
   this.loopNum = 0;
   this.period = 50;
   this.txt = '';
   this.tick();
   this.isDeleting = false;
};

TxtRotate.prototype.tick = function () {
   var i = this.loopNum % this.toRotate.length;
   var fullTxt = this.toRotate[i];

   if (this.isDeleting) {
      this.txt = fullTxt.substring(0, this.txt.length - 1);
   } else {
      this.txt = fullTxt.substring(0, this.txt.length + 1);
   }

   this.el.innerHTML = '<span class="wrap">' + this.txt + '</span>';

   var that = this;
   var delta = 300 - Math.random() * 100;

   if (this.isDeleting) {
      delta /= 2;
   }

   if (!this.isDeleting && this.txt === fullTxt) {
      delta = this.period;
      this.isDeleting = true;
   } else if (this.isDeleting && this.txt === '') {
      this.isDeleting = false;
      this.loopNum++;
      delta = 500;
   }

   setTimeout(function () {
      that.tick();
   }, delta);
};


var toastElList = [].slice.call(document.querySelectorAll('.toast'))


var toastBtn = document.getElementById('reportingAlert');
// var toastBtn = document.querySelectorAll('.toast-btn');
var toastList = toastElList.map(function (toastEl) {
  return new bootstrap.Toast(toastEl)
})

if (document.getElementById('reportingAlert')) {
   toastBtn.addEventListener('click', function () {
      toastList.forEach(toast => {
         toast.show()
      });
   })

}



var myModal = new bootstrap.Modal(document.getElementById('helpUs'))

window.onload = function () {
    var elements = document.getElementsByClassName('txt-rotate');
    for (var i = 0; i < elements.length; i++) {
      var toRotate = elements[i].getAttribute('data-rotate');
      var period = elements[i].getAttribute('data-period');
      if (toRotate) {
         new TxtRotate(elements[i], JSON.parse(toRotate), period);
      }
    }
    // INJECT CSS
    var css = document.createElement("style");
    css.type = "text/css";
    css.innerHTML = ".txt-rotate > .wrap { border-left: 0.08em solid #666 }";
    document.body.appendChild(css);

    // Help us know your wish open
    myModal.show()

};

