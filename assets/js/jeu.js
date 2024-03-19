let allPlayer = document.getElementById("listPlayer");
let code = document.getElementById("code");

function updateRoom(){
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.open("get","action/listPlayer.php?key=cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483&code="+code.innerText,true);
    xmlhttp.onreadystatechange = function(){
        if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
            allPlayer.innerHTML = "";
            let reponse = JSON.parse(this.response);
            console.log(reponse);
            reponse.forEach(element => {
                allPlayer.innerHTML += "<p>"+ element["joueur_pseudo"] + " </p>";
            });
        }
    }
    xmlhttp.send();
}
updateRoom();
setInterval(()=>{
    updateRoom();
},1000);