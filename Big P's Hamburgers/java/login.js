function jelszoMegjelenites() {
    var jelszo = document.getElementById("password");
    if (jelszo.type === "password") {
        jelszo.type = "text";
    } else {
        jelszo.type = "password";
    }
}