console.log("Run search.js");

if (jQuery) {
    console.log("jQuery est chargé");
}
else {
    console.log("jQuery n'est pas chargé");
}

$('#cities').prepend('<option selected=""></option>').select2({
    placeholder: 'Selectionnez le nom de la ville'
});