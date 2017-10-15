var chek = 1;

function checkb() {
    var chk = document.getElementsByClassName("chk");

    if (chek == 1) {
        for (var i = 0; i < chk.length; i++) {
            chk[i].checked = true;
        }
        chek = 0;
    }else {
        for (var i = 0; i < chk.length; i++) {
            chk[i].checked = false;
        }
        chek = 1;
    }
}