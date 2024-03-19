let allPlayer = document.getElementById("listPlayer");
let chatbox = document.getElementById("chat");
let msg = document.getElementById("chatBox");
let code = document.getElementById("code");

function updatePlayer(){
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

function updateChat(){
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

msg.addEventListener("keypress",(event)=>{
    if (event.key == "Enter" && msg.value!=""){
        sendChat(msg.value);
        msg.value="";
    }
})
updatePlayer();
updateChat();
setInterval(()=>{
    updatePlayer();
    updateChat();
},100);