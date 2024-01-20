const express = require('express');
var app = express();

var base64 = require('base-64');
var bodyParser = require('body-parser');
app.use(bodyParser.json({limit: '1050mb'}));
app.use(bodyParser.urlencoded({
    extended: true,
    limit: '1050mb'
}));

var fileUpload = require("express-fileupload");
app.use(fileUpload());

var server = app.listen(5000, function () {
    console.log("listening to port 5000.");
});

app.get('/',function(req,res){
    res.sendFile(__dirname +'/views/index.html');
});

var registration=require('./API/registration');

app.post("/registration",registration);

