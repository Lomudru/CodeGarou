let inGame = true;
let xmlhttp = new XMLHttpRequest;
// xmlhttp.open("get","action/getRole.php?key=cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483&code="+code.innerHTML, true);
xmlhttp.open("get","action/codeGarou.php?key=cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483&code="+code.innerHTML, true);
xmlhttp.onreadystatechange = function(){
    if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
        if(lauch == null){
            let reponse = JSON.parse(this.response);
            
            console.log("Vous etes "+reponse["role_nom"]);
            
            role = reponse["role_nom"];
            Game(2);
        }
    }
}
xmlhttp.send();
channelGame.bind('launch', function(data) {
    Object.values(data["Village"]).forEach(element => {
        if(element["joueur_id"] == sessionId){
            console.log("Vous etes villagois");
            role = "villagois";
        }
    });
    Object.values(data["Loup-Garou"]).forEach(element =>{
        if(element["joueur_id"] == sessionId){
            console.log("Vous etes Loup Garou");
            role = "Loup-Garou";
        }
    })
    if(data["delete"] != undefined){
        Game();
    }
});
channelGame.bind('lgVote', function(data) {
    if (role == "Loup-Garou"){
        console.log(data["user_pseudo"]+" a vote pour "+data["cible"])
    }
});
channelGame.bind('village', function(data) {
    console.log(data["user_pseudo"]+" a vote pour "+data["cible"])
});
channelGame.bind('game', function(data) {
    cible = undefined;
    if (data["tour"]!="g" && data["tour"]!="n" && data["tour"]!="m"){
        Game(data["ProchainRoleId"], data["tour"]);
    }else{
        if(data["tour"] == "g"){
            console.log("Fin. Le village a gagné")
        }
        else if(data["tour"] == "m"){
            console.log("Fin. Les loup ont gagnés")
        }
    }
});

function Game(RoleId = 2, tourDeJeu = 1, time = 10000){
    let xmlhttp = new XMLHttpRequest;

    if(RoleId == 1){
        tourWho = "village";
        console.log("Tour du village");
        console.log("Cliquer sur la personne que vous voulez tuer");
    
        setTimeout(()=>{
            tourWho = false;
            console.log("fin du tour");
            
            xmlhttp.open("get","action/codeGarou.php?key=cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483&code="+code.innerHTML+"&cible="+cible+"&tour="+tourDeJeu+"&action=VOTE&role=1", true);
            xmlhttp.onreadystatechange = function(){
                if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                    console.log(this.response);
                }
            }
            xmlhttp.send();
        }, time)
    }
    else if(RoleId == 2){
        console.log("Au tour des Loup Garou");
        if (role == "Loup-Garou"){
            tourWho = "loup";
            console.log("Cliquer sur la personne que vous voulez manger");
            
            setTimeout(()=>{
                tourWho = false;
                
                console.log("fin du tour");
                
                xmlhttp.open("get","action/codeGarou.php?key=cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483&code="+code.innerHTML+"&cible="+cible+"&tour="+tourDeJeu+"&action=LOUP_KILL&role=2", true);
                xmlhttp.onreadystatechange = function(){
                    if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                        console.log(this.response);
                    }
                }
                xmlhttp.send();
            }, time)
        }
    }
}
