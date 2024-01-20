var db = require('./db');
var mysql_format = require('mysql');
exports.ExecuteQuery = function(SQL, params, callback){
    console.log(mysql_format.format(SQL,params));
    db.getConnection(function(err, conn){
        if(err){
            exports.LogError(err);
            callback(err, null);
        }
        else{
            conn.query(SQL, params, function(err, results, fields){
                if(err){
                    exports.LogError(err);
                    callback(err, null, null);
                }
                else{
                    callback(null, results, fields);
                }
            });
            conn.release();
        }
    });
};

exports.LogError = function(err){
    console.log("[ERROR]: " + err);
}