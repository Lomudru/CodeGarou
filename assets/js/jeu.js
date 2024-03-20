let allPlayer = document.getElementById("listPlayer"),
    chatbox = document.getElementById("chat"),
    msg = document.getElementById("chatBox"),
    code = document.getElementById("code"),
    lauch = document.getElementById("lauch");

function afficherPlayer(){
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.open("get","action/listPlayer.php?key=cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483&code="+code.innerText,true);
    xmlhttp.onreadystatechange = function(){
        if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
            allPlayer.innerHTML = "";
            let reponse = JSON.parse(this.response);
            reponse.forEach(element => {
                allPlayer.innerHTML += "<p>"+ element["joueur_pseudo"] + " </p>";
            });
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
                chatbox.innerHTML += "<p>"+ element["joueur_pseudo"] + " a ecrit "+ element["chat_msg"]+" </p>";
            });
        }
    }
    xmlhttp.send();
}

function sendChat(message){
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.open("get","action/chat.php?key=cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483&code="+code.innerText+"&post="+encodeURIComponent(message),true);
    xmlhttp.send();
}
if(msg != null){
    msg.addEventListener("keypress",(event)=>{
        if (event.key == "Enter" && msg.value.trim() != ""){
            sendChat(msg.value);
            msg.value="";
        }
    });
}
if(lauch != null){
    lauch.addEventListener("click", ()=>{
        if(allPlayer.childNodes.length >= 4 && allPlayer.childNodes.length <= 30){
            let xmlhttp = new XMLHttpRequest();
            xmlhttp.open("get","action/jeu.php?key=cnuhdiaj3EJDHZIUAHIZ46826388634IE3886483&code="+code.innerText,true);
            xmlhttp.onreadystatechange = function(){
                if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                    let reponse = JSON.parse(this.response);
                }
            }
            xmlhttp.send();
        }
    });
}
// Enable pusher logging - don't include this in production
Pusher.logToConsole = true;

let pusher = new Pusher('e39c485e91fd48c1c3e7', {
cluster: 'eu'
});

let channelChat = pusher.subscribe(code.innerText);
channelChat.bind('chat', function(data) {
    chatbox.innerHTML += "<p>"+ data["user_pseudo"] + " a ecrit "+ data["message"]+" </p>";
});


let channelPlayer = pusher.subscribe(code.innerText);
channelPlayer.bind("player", function(data){
    if(data["disconnect"] == undefined){
        allPlayer.innerHTML += "<p>"+ data["user_pseudo"] + "</p>";
    }else{
        for(let i = 0; i < allPlayer.childNodes.length; i++){
            if(allPlayer.childNodes[i].innerText == data["user_pseudo"]){
                allPlayer.childNodes[i].innerHTML = "";
            }
        }
    }
})

afficherPlayer();
afficherChat();