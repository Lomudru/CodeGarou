let allPlayer = document.getElementById("listPlayer"),
    chatbox = document.getElementById("chat"),
    msg = document.getElementById("chatBox"),
    submit = document.getElementById("submit"),
    code = document.getElementById("code"),
    lauch = document.getElementById("lauch"),
    tourWho,
    cible = undefined,
    role,
    hide = document.getElementById("hideCode"),
    tempCode = code.innerText,
    hideChat = document.getElementById("hideChat"),
    chatHolder = document.getElementById("chatHolder"),
    rotate = 0;


hideChat.addEventListener("click", ()=>{
    rotate += 180;
    chatHolder.classList.toggle("small");
    hideChat.style.transform = "rotate("+ rotate +"deg)";
})
hide.addEventListener("click", ()=>{
    console.log("click")
    if(code.innerText == "*****"){
        code.innerText = tempCode;
    }else{
        code.innerText = "*****";   
    }
});


function afficherPlayer(){
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.open("get","action/listPlayer.php?key=cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483&code="+code.innerText,true);
    xmlhttp.onreadystatechange = function(){
        if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
            allPlayer.innerHTML = "";
            let reponse = JSON.parse(this.response);
            reponse.forEach(element => {
                if(element["en_vie"] == 1){
                    allPlayer.innerHTML += "<p id='"+element["joueur_pseudo"]+"'>"+ element["joueur_pseudo"] + " </p>";
                    
                }else{
                    allPlayer.innerHTML += "<p id='"+element["joueur_pseudo"]+"'>"+ element["joueur_pseudo"] + " est mort</p>";
                }
            });
            allPlayer.childNodes.forEach(element => {
                element.addEventListener("click", clickJoueur);
            })
            
        }
    }
    xmlhttp.send();
}

function afficherChat(){
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.open("get","action/chat.php?key=cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483&code="+code.innerText,true);
    xmlhttp.onreadystatechange = function(){
        if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
            chatbox.innerHTML = "";
            let reponse = JSON.parse(this.response);
            reponse.forEach(element => {
                chatbox.innerHTML += "<p>"+ element["joueur_pseudo"] + " : "+ element["chat_msg"]+" </p>";
            });
            setTimeout(()=>{
                chatbox.scrollTop = chatbox.scrollHeight;
            }, 40);
        }
    }
    xmlhttp.send();
}

function sendChat(message){
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.open("get","action/chat.php?key=cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483&code="+code.innerText+"&post="+encodeURIComponent(message),true);
    xmlhttp.send();
    setTimeout(()=>{
        chatbox.scrollTop = chatbox.scrollHeight;
    }, 500);
}
if(msg != null){
    msg.addEventListener("keypress",(event)=>{
        if (event.key == "Enter" && msg.value.trim() != ""){
            sendChat(msg.value);
            msg.value="";
        }
    });
    submit.addEventListener("click", ()=>{
        sendChat(msg.value);
        msg.value = "";
    })
}
// Enable pusher logging - don't include this in production
Pusher.logToConsole = true;

let pusher = new Pusher('e39c485e91fd48c1c3e7', {
cluster: 'eu'
});
let channelGame = pusher.subscribe(code.innerText);
if(lauch != null){
    lauch.addEventListener("click", ()=>{
        if(allPlayer.childNodes.length >= 2 && allPlayer.childNodes.length <= 30){
            lauch.remove();
            let xmlhttp = new XMLHttpRequest();
            xmlhttp.open("get","action/jeu.php?key=cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483&code="+code.innerText,true);
            xmlhttp.onreadystatechange = function(){
                if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                    console.log(this.response);
                }
            }
            xmlhttp.send();
        }
    });
    channelGame.bind('launch', function(data) {
        if(data["delete"] != undefined){
            lauch.remove();
        }
    });
}


channelGame.bind('game', function(data) {
    afficherPlayer();
});
channelGame.bind('chat', function(data) {
    chatbox.innerHTML += "<p>"+ data["user_pseudo"] + " : "+ data["message"]+" </p>";
    
});


let channelPlayer = pusher.subscribe(code.innerText);
channelPlayer.bind("player", function(data){
    if(data["disconnect"] == undefined){
        allPlayer.innerHTML += "<p id='"+ data["user_pseudo"] +"'>"+ data["user_pseudo"] + "</p>";
    }else{
        for(let i = 0; i < allPlayer.childNodes.length; i++){
            if(allPlayer.childNodes[i].innerText == data["user_pseudo"]){
                allPlayer.childNodes[i].remove();
            }
        }
    }
    allPlayer.childNodes.forEach(element => {
        element.addEventListener("click", clickJoueur);
    })
})

function clickJoueur(){
    let xmlhttp = new XMLHttpRequest();
    if(tourWho == "loup" && role == "Loup-Garou"){
        cible = this.id;
        xmlhttp.open("get","action/codeGarou.php?key=cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483&code="+code.innerHTML+"&cible="+cible+"&cibleTempo=true&role=2", true);

        xmlhttp.send();
    }
    else if(tourWho=="village"){
        cible = this.id;
        xmlhttp.open("get","action/codeGarou.php?key=cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483&code="+code.innerHTML+"&cible="+cible+"&cibleTempo=true&role=1", true);
        xmlhttp.send();
    }
}


afficherPlayer();
afficherChat();