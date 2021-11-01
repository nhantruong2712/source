var app = require('express').createServer();
var io = require('socket.io').listen(app);
 
app.listen(30000);
 
// usernames which are currently connected to the chat
var usernames = {};
 
io.sockets.on('connection', function (socket) {
    //console.log(socket)
     
    socket.on('send', function (data, room) {
        if(room != undefined)
            io.to(room).emit('emit', data);
        else if(socket.room)
            io.to(socket.room).emit('emit', data);
        else 
            io.sockets.emit('emit', data);
    });
    
    socket.on('join', function (data) {
        socket.room = data
        socket.join(data);
    });
 
});

var nsp = io.of('/sale');
nsp.on('connection', function(socket){
    socket.on('send', function (data, room) {    
        if(room != undefined)
            nsp.to(room).emit('emit', data);
        else if(socket.room)
            nsp.to(socket.room).emit('emit', data);
        else 
            nsp.emit('emit', data);
    });
    
    socket.on('join', function (data) {
        socket.room = data
        socket.join(data);
    });
});

var nsp2 = io.of('/kitchen');
nsp2.on('connection', function(socket){
    socket.on('send', function (data, room) {         
        if(room != undefined)
            nsp2.to(room).emit('emit', data);
        else if(socket.room)
            nsp2.to(socket.room).emit('emit', data);
        else 
            nsp2.emit('emit', data);
    });
    
    socket.on('join', function (data) {
        socket.room = data
        socket.join(data);
    });
});