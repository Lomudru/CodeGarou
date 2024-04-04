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
                if(element["room_visibilite"] == 1 && element["room_en_jeu"] == 0){
                    allRoom.innerHTML += "<a id='"+ element["room_code"] +"' href='jeu.php?code="+ element["room_code"] +"'>"+ element["room_nom"] + " : nombre de joueur max " + element["room_nbr_max"] + "</a>";
                }
            });
        }
    }
    xmlhttp.send();
}
updateRoom();
// Enable pusher logging - don't include this in production
Pusher.logToConsole = true;

let pusher = new Pusher('e39c485e91fd48c1c3e7', {
cluster: 'eu'
});


let channelRoom = pusher.subscribe("Channel_room");
channelRoom.bind('room', function(data) {
    if(data["delete"] != undefined){
        for(let i = 0; i < allRoom.childNodes.length; i++){
            if(allRoom.childNodes[i].id == data["room_code"]){
                allRoom.childNodes[i].remove();
            }
        }
    }
    else if(data["visibility"] == 1 && data["delete"] == undefined){
        allRoom.innerHTML += "<a id='"+ data["room_code"] +"' href='jeu.php?code="+ data["room_code"] +"'>"+ data["room_nom"] + " : nombre de joueur max " + data["nbr_max"] + "</a>";
    }
});
rechercheRoom.childNodes[1].addEventListener("keyup",()=>{
    if(rechercheRoom.childNodes[1].value.length >= 5){
        rechercheRoom.submit();
    }
})