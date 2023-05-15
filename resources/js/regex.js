let elemPhoneFormat = $('.phoneFormat');
for (let a = 0; a < elemPhoneFormat.length; a++) {
    var id = elemPhoneFormat[a].id;
    $('#'+id).on('input', (e) => {
        let res = e.target.value;
        res = res.replace(/\D+/, '').replace(/[^a-zA-Z0-9_-]/g,'').replace(/^0+/, '');

        e.target.value = res;
    });
}

let elemPassword = $('.password');
for (let b = 0; b < elemPassword.length; b++) {
    var id_p = elemPassword[b].id;
    $('#'+id_p).on('input', (e) => {
        var res = e.target.value;
        res = res.replace(' ', '').replace(/[^\w\s]/gi, '')
        .replace('_', '');
        e.target.value = res;
    });
}

let elemOnlyNumber = $('.onlyNumber');
for (let c = 0; c < elemOnlyNumber.length; c++) {
    var id_n = elemOnlyNumber[c].id;
    $('#'+id_n).on('input', (e) => {
        var res = e.target.value;
        res = res.replace(/\D+/, '').replace(/[^a-zA-Z0-9_-]/g,'');

        e.target.value = res;
    })
}
