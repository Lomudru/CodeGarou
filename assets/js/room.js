let allRoom = document.getElementById("allRoom"),
    rechercheRoom = document.getElementById("search");

function updateRoom(){
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.open("get","action/room.php?key=cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483",true);
    xmlhttp.onreadystatechange = function(){
        if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
            allRoom.innerHTML = "";
            let reponse = JSON.parse(this.response);
            reponse.forEach(element => {
                if(element["room_visibilite"] == 1){
                    allRoom.innerHTML += "<a href='jeu.php?code="+ element["room_code"] +"'>"+ element["room_nom"] + " : nombre de joueur max " + element["room_nbr_max"] + "</a>";
                }
            });
        }
    }
    xmlhttp.send();
}
updateRoom();
setInterval(()=>{
    updateRoom();
},1000);
rechercheRoom.childNodes[1].addEventListener("keyup",()=>{
    if(rechercheRoom.childNodes[1].value.length >= 5){
        rechercheRoom.submit();
    }
})