function passval(){ 
    var selecttext = document.getElementById('cs').value;
    sessionStorage.setItem("value", selecttext);
    return false;
}