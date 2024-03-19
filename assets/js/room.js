let allRoom = document.getElementById("allRoom");

function updateRoom(){
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.open("get","action/room.php?key=cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483",true);
    xmlhttp.onreadystatechange = function(){
        if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
            allRoom.innerHTML = "";
            let reponse = JSON.parse(this.response);
            reponse.forEach(element => {
                allRoom.innerHTML += "<p>"+ element["room_nom"] + " : nombre de joueur max " + element["room_nbr_max"];
            });            
        }
    }
    xmlhttp.send();
}
updateRoom();
setInterval(()=>{
    updateRoom();
},1000);