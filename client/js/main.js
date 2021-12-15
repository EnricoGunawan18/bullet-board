const url = 'http://localhost:8080/api/page';

function format(id, title, message) {
    let card = '';
    card += '<li id="post-' + id + '" class="card">';
    card += '<textarea class="left" id = "message' + id + '">' + message + '</textarea>';
    card += '<div class="right"><div class="button"><button class="edit" onclick="edit(' + id +')">編集</button><button class="delete" onclick="erase(' + id + ')">削除</button></div><textarea class="rightbottom" id = "name' + id + '">' + title + '</textarea></div>'
    card += '</li>';
    return card;
}

$.ajax({
    type: 'GET',
    url: url
}).done(function(data){
    data = JSON.parse(data)
    data.forEach(function(post) {
        $('#list').append(format(post[0], post[1], post[2]));
    });
});



function post(){
    let title = $('#name1').val();
    let message = $('#message1').val();

    $.ajax({
        type: 'POST',
        url: url,
        data: {
            "name": title,
            "messages": message
        }
    }).done(function(data){
        window.location.reload();
    });
}
function edit($id){
    $.ajax({
        type: 'POST',
        url : 'http://localhost:8080/api/page/put', 
        data: {
            "id" : $id,
            "name" : $('#name' + $id).val(),
            "messages" : $('#message' + $id).val()
        }
    }).done(function(data){
         window.location.reload();
     });
}
function erase($id){

 $.ajax({
     url : 'http://localhost:8080/api/page/delete', 
     type : 'POST',
     data : {
         "id" : $id
     }
    }).done(function(data){
        window.location.reload();
    });
}