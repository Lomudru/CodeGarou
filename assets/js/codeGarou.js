let inGame = true;
let xmlhttp = new XMLHttpRequest;
let action = document.getElementById("allAction");
let roleZone = document.getElementById("roleZone");
xmlhttp.open("get","action/getRole.php?key=cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483&code="+code.innerHTML, true);
xmlhttp.onreadystatechange = function(){
    if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
        if(lauch == null){
            let reponse = JSON.parse(this.response)[0];
            roleZone.classList.remove("hidden");
            role = reponse["role_nom"];
            roleZone.innerText = role;
            console.log(role);
            Game(2);
        }
    }
}
xmlhttp.send();
channelGame.bind('launch', function(data) {
    Object.values(data["Village"]).forEach(element => {
        if(element["joueur_id"] == sessionId){
            roleZone.classList.remove("hidden");
            roleZone.innerText = "Villagois";
            role = "villagois";
        }
    });
    Object.values(data["Loup-Garou"]).forEach(element =>{
        if(element["joueur_id"] == sessionId){
            roleZone.classList.remove("hidden");
            roleZone.innerText = "Loup Garou";
            role = "Loup-Garou";
        }
    })
    if(data["delete"] != undefined){
        Game();
    }
});
channelGame.bind('lgVote', function(data) {
    if (role == "Loup-Garou"){
        action.innerHTML += "<p>" + data["user_pseudo"]+" a vote pour "+data["cible"] + "</p>";
        action.scrollTop = -action.scrollHeight;
    }
});
channelGame.bind('village', function(data) {
    action.innerHTML += "<p>" + data["user_pseudo"]+" a vote pour "+data["cible"] + "</p>";
    action.scrollTop = -action.scrollHeight;
});
channelGame.bind('game', function(data) {
    roleZone.classList.remove("hidden");
    roleZone.innerText = role;
    cible = undefined;
    if (data["tour"]!="g" && data["tour"]!="n" && data["tour"]!="m"){
        Game(data["ProchainRoleId"], data["tour"]);
    }else{
        if(data["tour"] == "g"){
            action.innerHTML += "<p>Fin. Le village a gagné</p>";
            action.scrollTop = -action.scrollHeight;
        }
        else if(data["tour"] == "m"){
            action.innerHTML += "<p>Fin. Les loup ont gagnés</p>";
            action.scrollTop = -action.scrollHeight;
        }
    }
});

function Game(RoleId = 2, tourDeJeu = 1, time = 10000){
    let xmlhttp = new XMLHttpRequest;

    if(RoleId == 1){
        tourWho = "village";
        action.innerHTML += "<p>Tour du village</p>";
        action.innerHTML += "<p>Cliquer sur la personne que vous voulez tuer</p>";
    
        setTimeout(()=>{
            tourWho = false;
            action.innerHTML += "<p>fin du tour</p>";
            
            xmlhttp.open("get","action/codeGarou.php?key=cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483&code="+code.innerHTML+"&cible="+cible+"&tour="+tourDeJeu+"&action=VOTE&role=1", true);
            xmlhttp.send();
        }, time)
    }
    else if(RoleId == 2){
        action.innerHTML += "<p>Au tour des Loup Garou</p>";
        if (role == "Loup-Garou"){
            tourWho = "loup";
            action.innerHTML += "<p>Cliquer sur la personne que vous voulez manger</p>";
            
            setTimeout(()=>{
                tourWho = false;
                
                action.innerHTML += "<p>fin du tour</p>";
                
                xmlhttp.open("get","action/codeGarou.php?key=cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483&code="+code.innerHTML+"&cible="+cible+"&tour="+tourDeJeu+"&action=LOUP_KILL&role=2", true);
                xmlhttp.send();
            }, time)
        }
    }
    action.scrollTop = -action.scrollHeight;
}
