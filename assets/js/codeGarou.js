let role;
let inGame = true;
let xmlhttp = new XMLHttpRequest;
xmlhttp.open("get","action/getRole.php?key=cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483&code="+code.innerHTML, true);
xmlhttp.onreadystatechange = function(){
    if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
        if(lauch == null){
            // console.log(this.response);
            let reponse = JSON.parse(this.response);
        }
    }
}
xmlhttp.open("get","action/codeGarou.php?key=cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483&code="+code.innerHTML, true);
xmlhttp.onreadystatechange = function(){
    if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
        if(lauch == null){
            let reponse = JSON.parse(this.response);
            console.log("")
            console.log("Vous etes "+reponse["role_nom"]);
            console.log("")
            role = reponse["role_nom"];
            Game(2);
        }
    }
}
xmlhttp.send();

let channelGame = pusher.subscribe(code.innerText);
channelGame.bind('launch', function(data) {
    Object.values(data["Village"]).forEach(element => {
        if(element["joueur_id"] == sessionId){
            console.log("")
            console.log("Vous etes villagois");
            console.log("")
            role = "villagois";
        }
    });
    Object.values(data["Loup-Garou"]).forEach(element =>{
        if(element["joueur_id"] == sessionId){
            console.log("")
            console.log("Vous etes Loup Garou");
            console.log("")
            role = "Loup-Garou";
        }
    })
    if(data["delete"] != undefined){
        Game();
    }
});
channelGame.bind('lgVote', function(data) {
    if (role == "Loup-Garou"){
        console.log("")
        console.log(data["user_pseudo"]+" a vote pour "+data["cible"])
        console.log("")
    }
});
channelGame.bind('game', function(data) {
    // console.log("nouveau tour de "+data["ProchainRoleId"]);
    // console.log(data["tour"]);
    Game(data["ProchainRoleId"], data["tour"]);
});

function Game(RoleId = 2, tourDeJeu = 1, time = 10000){
    // console.log(role);
    let xmlhttp = new XMLHttpRequest;
    let tourWho;
    let cible;
    allPlayer.childNodes.forEach(element => {
        element.addEventListener("click", () =>{
            if(tourWho == "loup"){ // definit ligne 87, 75 et 93
                cible = element.innerText;
                xmlhttp.open("get","action/codeGarou.php?key=cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483&code="+code.innerHTML+"&cible="+cible+"&cibleTempo=true", true);
                xmlhttp.onreadystatechange = function(){
                    if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                        console.log("")
                        console.log(this.response);
                        console.log("")
                    }
                }
                xmlhttp.send();
            }
        })
    })
    if(RoleId == 1){
        
        console.log("")
        console.log("Tour du village");
        console.log("Cliquer sur la personne que vous voulez tuer");
        console.log("")
        
        setTimeout(()=>{
            tourWho = false;
            console.log("")
            console.log("fin du tour");
            console.log("")
            xmlhttp.open("get","action/codeGarou.php?key=cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483&code="+code.innerHTML+"&cible="+cible+"&tour="+tourDeJeu+"&action=VOTE&role=1", true);
            xmlhttp.onreadystatechange = function(){
                if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                    console.log("")
                    console.log(this.response);
                    console.log("")
                }
            }
            xmlhttp.send();
        }, time)
    }
    else if(RoleId == 2){
        console.log("")
        console.log("Au tour des Loup Garou");
        console.log("")
        if (role == "Loup-Garou"){
            tourWho = "loup";
            console.log("")
            console.log("Cliquer sur la personne que vous voulez tuer");
            console.log("")
            
            setTimeout(()=>{
                tourWho = false;
                console.log("")
                console.log("fin du tour");
                console.log("")
                xmlhttp.open("get","action/codeGarou.php?key=cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483&code="+code.innerHTML+"&cible="+cible+"&tour="+tourDeJeu+"&action=LOUP_KILL&role=2", true);
                xmlhttp.onreadystatechange = function(){
                    if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                        console.log("")
                        console.log(this.response);
                        console.log("")
                    }
                }
                xmlhttp.send();
            }, time)
        }
    }

}
