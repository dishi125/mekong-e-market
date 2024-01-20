const mysql = require('mysql');

require('dotenv').config();

const config = {
    "host": process.env.DB_HOST,
    "user": process.env.DB_USERNAME,
    "password": process.env.DB_PASSWORD,
    "database": process.env.DB_DATABASE
};

var connection  = mysql.createConnection({
    host: config.host,
    user: config.user,
    password: config.password,
    database: config.database
});
connection.connect(function (error) {
    if (!!error)
        throw error;

    console.log('mysql connected to ' + config.host + ", user " + config.user + ", database " + config.database);
});
// const pool = mysql.createPool(connection);

module.exports=connection;
// module.exports = pool;