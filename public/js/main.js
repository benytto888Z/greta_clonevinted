$(document).ready(function(){
//	"use strict";
console.log("hoooooo");
$('.custom-file-input').on('change', function(event) {
  var inputFile = event.currentTarget;
  
  $(inputFile).parent()
      .find('.custom-file-label')
      .html(inputFile.files[0].name);
});

$('label.custom-file-label::after').html("Parcourir");
});