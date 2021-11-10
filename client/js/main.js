var script = document.createElement('script');
script.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
script.type = 'text/javascript';
document.getElementsByTagName('head')[0].appendChild(script);

function post(){
    //var xhr = new XMLHttpRequest();
    //var url = 'http://localhost:8080/api';
    var name = document.getElementById('name1').value;
    var message = document.getElementById('message1').value;

    //xhr.open("POST",url);

    //var data = {name:name,messages:message};

    xhr.send(data);
    alert(name);
}
function edit(){
    alert("edit");
}
function erase(){
    alert("delete");
}