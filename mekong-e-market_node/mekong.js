const express = require('express'),
    socket = require('socket.io'),
    mysql = require('mysql');
var base64 = require('base-64');
var dateFormat = require('dateformat');
var randomize = require('randomatic');
var moment = require('moment-timezone');
const cron = require('node-cron');
var dateTime = require("node-datetime");
const dateformat = require('dateformat');
const axios = require('axios').default;
const timeInterval = 60000;
const user_type = {0:"Not Defined", 1:"Farmer", 2:"Retailer", 3:"Wholesaler", 4:"Buyer"};

var app = express();

var server = app.listen(5555, function () {
    console.log("listening to port 5555.");
});
var io = socket(server);
require('dotenv').config();
var bodyParser = require('body-parser');
app.use(bodyParser.json({limit: '50mb'}));
app.use(bodyParser.urlencoded({
    extended: true,
    limit: '50mb'
}));
const config = {
    "host": process.env.DB_HOST,
    "user": process.env.DB_USERNAME,
    "password": process.env.DB_PASSWORD,
    "base": process.env.DB_DATABASE
};

var db = mysql.createConnection({
    host: config.host,
    user: config.user,
    password: config.password,
    database: config.base
});

var path = process.env.APP_URL;
var api_path = process.env.APP_URL_API;

db.connect(function (error) {
    if (!!error)
        throw error;

    console.log('mysql connected to ' + config.host + ", user " + config.user + ", database " + config.base);
});
app.get('/',function(req,res){
    res.sendFile(__dirname +'/views/index2.html');
});

let post_ids = {};
let socket_ids = [];
app.get("/home",function(req,res){

    return res.json("hi");
});
io.on('connection', function (socket) {
    console.log("connection : "+socket.id);
    let liveViewerInterval;
    let homeInterval;
    let liveInterval;
    let upcomingInterval;
    let sellerUpcomingInterval;
    //add live-viewer
    socket.on("live_viewer",function(data) {
        data = JSON.parse(data);
        post_ids[data.post_id] = (post_ids[data.post_id] || 0) + 1;
        live_viewer(data);
    });
    function live_viewer(data) {
        socket.emit("live_viewer1",JSON.stringify({'Response':true,'ResponseCode' : 1,'data':{'post_id':data.post_id,'live_viewer_count':post_ids[data.post_id]}}));
        liveViewerInterval = setTimeout(live_viewer, 500,data);
    }

    //show live-viewer in admin panel
    socket.on("live_viewer_admin",function(data) {
        data = JSON.parse(data);
        post_ids[data.post_id] = post_ids[data.post_id] || 0;
        socket.emit("live_viewer_admin1",JSON.stringify({'Response':true,'ResponseCode' : 1,'data':{'post_id':data.post_id,'live_viewer_count':post_ids[data.post_id]}}));
    });

    //delete live-viewer while
    socket.on("delete_viewer",function(data){
        data = JSON.parse(data);
        post_ids[data.post_id] = (post_ids[data.post_id] || 1) - 1;
        socket.emit("delete_viewer1",JSON.stringify({'Response':true,'ResponseCode' : 1,'data':{'post_id':data.post_id,'live_viewer_count':post_ids[data.post_id]}}));
    });

    //home api
    socket.on("home",function(data) {

        if(socket_ids.includes(socket.id)){
            clearTimeout(homeInterval);
        }else {
            socket_ids.push(socket.id);
        }

        //parse json data
        data = JSON.parse(data);
        get_home_data(data);
    });
    function get_home_data(data) {

        let user_id = data.user_id;
        let state_id = (data.state_id) ? data.state_id : null;
        let area_id = (data.area_id) ? data.area_id : null;
        let search = (data.search) ? data.search : null;
        let time = (data.time) ? data.time : null;
        let sub_cat_ids = (data.sub_cat_id) ? data.sub_cat_id : {};
        let time_zone = (data.time_zone) ? data.time_zone : process.env.TIME_ZONE;
        let is_preferred = (data.is_preferred) ? data.is_preferred : null;
        let per_page = 10;

        console.log("-----------Home-start----------------");
        getUserSubCatId(user_id, sub_cat_ids, function (sub_cat_id) {
            time_array(time_zone,function(time_array){
                getBanners(type="top",time_zone,function(top_ads){
                    getTrade(type="HomeLive",user_id,sub_cat_id['0'],per_page,time_zone,time_array,function(live){
                        getTrade(type="HomeEnd",user_id,sub_cat_id['1'],per_page,time_zone,time_array,function(endTrade){
                            getTrade(type="HomeUpcomming",user_id,sub_cat_id['2'],per_page,time_zone,time_array,function(upComing){
                                getBanners(type="middle",time_zone,function(middle_ads) {
                                    getTopSellers(function (topSellers) {
                                        getlogistics(function (logistics) {
                                            let HomeDataArray = {
                                                'Response': true,
                                                'ResponseCode': 1,
                                                'top_ads': top_ads,
                                                'date': live['time_array'][0],
                                                'time': live['time_array'][1],
                                                'live_trade': live['HomeLive'],
                                                'upcoming_trade': upComing['HomeUpcomming'],
                                                'upcoming_trade_time_array': upComing['time_array'],
                                                'ended_trade': endTrade,
                                                'mid_ads': middle_ads,
                                                'top_seller': topSellers,
                                                'logistics': logistics
                                            };
                                            socket.emit("home1", JSON.stringify(HomeDataArray));
                                        });
                                    });
                                });
                            }, state_id, area_id, is_preferred, search, time);
                        }, state_id, area_id, is_preferred, search);
                    }, state_id, area_id, is_preferred, search);
                });
            });
        });

        //set interval of 1-min
        var dt1=new Date();
        var  diffInterval = (60 - Number(dateFormat(dt1, "ss"))) * 1000;

        if(timeInterval != diffInterval)
        {

            homeInterval = setTimeout(get_home_data, diffInterval,data);
            console.log(diffInterval + "get_home_data");
            diffInterval=timeInterval;
        }
        else{

            homeInterval = setTimeout(get_home_data, timeInterval,data);
        }
    }

    //live trade api with/without filtration
    socket.on("live_trade",function(data) {

        if(socket_ids.includes(socket.id)){
            clearTimeout(liveInterval);
        }else {
            socket_ids.push(socket.id);
        }

        //parse json data
        data = JSON.parse(data);
        get_Live_trade(data,"live_trade1","HomeLive");
    });
    socket.on("seller_live_trade",function(data) {

        if(socket_ids.includes(socket.id)){
            clearTimeout(liveInterval);
        }else {
            socket_ids.push(socket.id);
        }

        //parse json data
        data = JSON.parse(data);
        get_Live_trade(data,"seller_live_trade1","HomeLiveSeller");
    });
    function get_Live_trade(data,res_name,type) {
        let user_id = data.user_id;
        let sub_cat_id = (data.sub_cat_id) ? data.sub_cat_id : null;
        let state_id = (data.state_id) ? data.state_id : null;
        let area_id = (data.area_id) ? data.area_id : null;
        let search = (data.search) ? data.search : null;
        let time_zone = (data.time_zone) ? data.time_zone : process.env.TIME_ZONE;
        let is_preferred = (data.is_preferred) ? data.is_preferred : null;
        let per_page = (data.per_page) ? data.per_page : null;

        time_array(time_zone,function(time_array) {
            getTrade(type, user_id, sub_cat_id, per_page, time_zone, time_array, function (LiveData) {
                let dataArray = {
                    'Response': true,
                    'ResponseCode': 1,
                    'date': LiveData['time_array'][0],
                    'time': LiveData['time_array'][1],
                    'live_trade': LiveData[type]
                };
                socket.emit(res_name, JSON.stringify(dataArray));
            }, state_id, area_id, is_preferred, search)
        });
        var dt1=new Date();
        var  diffInterval = (60 - Number(dateFormat(dt1, "ss"))) * 1000;
        if(timeInterval != diffInterval)
        {
            console.log(diffInterval + "get_Live_trade");
            liveInterval = setTimeout(get_Live_trade, diffInterval,data,res_name,type);
            diffInterval=timeInterval;
        }
        else{


            liveInterval = setTimeout(get_Live_trade, timeInterval,data,res_name,type);
        }

    }

    //upcoming trade api with/without filtration
    socket.on("get_upcoming_trade_post",async function(data) {

        if(socket_ids.includes(socket.id)){
            clearTimeout(upcomingInterval);
        }else {
            socket_ids.push(socket.id);
        }
        data = JSON.parse(data);
        await get_upcoming_trade(data,"get_upcoming_trade_post_response");
    });
    async function get_upcoming_trade(data,res_name) {

        try {
            let response = await axios.post(api_path +'get_upcoming_trade_post', data);
            socket.emit(res_name,JSON.stringify(response.data));
        } catch (error) {
            socket.emit(res_name,JSON.stringify({'Response':false,'ResponseCode' : 0,'ResponseMessage':error.message}));
        }
        var dt1=new Date();
        var  diffInterval = (60 - Number(dateFormat(dt1, "ss"))) * 1000;
        if(timeInterval != diffInterval)
        {
            upcomingInterval = setTimeout(get_upcoming_trade, diffInterval,data,res_name);
            console.log(diffInterval +"get_upcoming_trade");
            diffInterval=timeInterval;
        }
        else{
            upcomingInterval = setTimeout(get_upcoming_trade, timeInterval,data,res_name);
        }

    }

    //seller-upcoming trade api with/without filtration
    socket.on("get_seller_upcoming_posts",async function(data) {

        if(socket_ids.includes(socket.id)){
            clearTimeout(sellerUpcomingInterval);
        }else {
            socket_ids.push(socket.id);
        }
        data = JSON.parse(data);
        await get_seller_upcoming_posts(data,"get_seller_upcoming_posts_response");
    });
    async function get_seller_upcoming_posts(data,res_name) {

        try {
            let response = await axios.post(api_path +'get_seller_upcoming_posts', data);
            socket.emit(res_name,JSON.stringify(response.data));
        } catch (error) {
            socket.emit(res_name,JSON.stringify({'Response':false,'ResponseCode' : 0,'ResponseMessage':error.message}));
        }
        var dt1=new Date();
        var  diffInterval = (60 - Number(dateFormat(dt1, "ss"))) * 1000;
        if(timeInterval != diffInterval)
        {

            sellerUpcomingInterval = setTimeout(get_seller_upcoming_posts, diffInterval,data,res_name);
            console.log(diffInterval + "get_seller_upcoming_posts");
            diffInterval=timeInterval;
        }
        else{

            sellerUpcomingInterval = setTimeout(get_seller_upcoming_posts, timeInterval,data,res_name);
        }

    }

    socket.on('disconnect', () => {
        console.log("disconnect : "+socket.id);
        clearTimeout(liveViewerInterval);
        clearTimeout(homeInterval);
        clearTimeout(liveInterval);
        clearTimeout(upcomingInterval);
        clearTimeout(sellerUpcomingInterval);
    });
});

function getUserSubCatId(user_id, sub_cat_ids, callback) {
    // db.query("select sub_category_id from user_profiles where id = ?",[user_id],function(err,sub_category_id){
    //     if(!!err) throw err;
    //     if(sub_category_id.length==0)
    //     {
    //         return callback(sub_category_id);
    //     }
    //     else
    //     {
    //         let sub_cat = sub_category_id[0].sub_category_id;
    let sub_cat = null;
    if(!sub_cat_ids['0']){//Live trade
        sub_cat_ids['0'] = sub_cat;
    }
    if(!sub_cat_ids['1']){//Ended trade
        sub_cat_ids['1'] = sub_cat;
    }
    if(!sub_cat_ids['2']){//Upcoming trade
        sub_cat_ids['2'] = sub_cat;
    }

    return callback(sub_cat_ids);
    //     }
    // });
}

function getTopSellers(callback) {

    let sql = "select user_profiles.id,user_profiles.name,user_profiles.profile_pic,user_profiles.user_type," +
        "user_profiles.main_category_id,user_profiles.sub_category_id," +
        "(select name from states where states.id = user_profiles.state_id) AS state,"+
        "(select name from areas where areas.id = user_profiles.area_id) AS area,"+
        "user_profiles.address as company_address, " +
        " avg(rate) as average_rate," +
        "count('review') as total_reviews from " +
        "`ratings` left join `user_profiles` on `ratings`.`seller_id` = `user_profiles`.`id` " +
        "group by seller_id order by `average_rate` desc";

    db.query(sql,[],

        function(err,sellers){

            if(!!err) throw err;
            if(sellers.length==0) {
                return callback(sellers);

            } else {
                for(var p in sellers) {
                    sellers[p]['profile_pic'] = path + sellers[p]['profile_pic'];
                    sellers[p]['user_type'] = user_type[sellers[p]['user_type']];
                }
                return callback(sellers);
            }
        });
}

function getlogistics(callback) {

    db.query("SELECT logistic_companies.id,logistic_companies.name,logistic_companies.reg_no," +
        "logistic_companies.id_no,logistic_companies.contact,logistic_companies.email, " +
        "logistic_companies.state_id,logistic_companies.area_id," +
        "(select name from states where states.id = logistic_companies.state_id) AS state,"+
        "(select name from areas where areas.id = logistic_companies.area_id) AS area, "+
        "logistic_companies.address, " +
        "logistic_companies.description,logistic_companies.nursery,logistic_companies.exporter_status, " +
        "logistic_companies.profile,logistic_companies.status " +
        "FROM `logistic_companies` where logistic_companies.status=1",[],

        async function(err,logistic){

            if(!!err) throw err;
            if(logistic.length==0)
            {
                return callback(logistic);
            }
            else
            {
                for(var l in logistic)
                {
                    logistic[l]['profile']= path + logistic[l]['profile'];
                    logistic[l]['logistic_company_photos']= await get_logistics_company_photos(logistic[l]['id']);
                }
                return callback(logistic);
            }
        });
}

//old banner function
/*
var getBanners = function(type,time_zone,callback) {

    let dt = new Date();
    let date = dateFormat(dt, "UTC:yyyy-mm-dd");

    db.query("select name,contact,email,location,price,duration,duration_type," +
        "start_date," +
        "ADDTIME(start_date,SEC_TO_TIME(duration)) as end_date," +
        "banner_link,type," +
        "CONCAT(?,banner_photo) as banner_photo " +
        "from banners " +
        "having ? between start_date AND end_date ",[path,date],

        function(err,banners){
            if(!!err) throw err;

            if(banners.length==0) {
                return callback(banners);
            }
            else {
                for(var i in banners) {
                    banners[i]['start_date'] = moment(banners[i]['start_date']).tz(time_zone).format('D.M.YYYY \\a\\t h.ma');
                    banners[i]['end_date'] = moment(banners[i]['end_date']).tz(time_zone).format('D.M.YYYY');
                }
                return callback(banners);
            }
        });
};
*/
//new banner function
var getBanners= function(type,time_zone,callback) {
    let dt = new Date();
    let date = dateFormat(dt, "UTC:yyyy-mm-dd");
    if(type=="top")
    {

        db.query("select name,contact,email,location,price,duration,duration_type," +
            "start_date," +
            "ADDTIME(start_date,SEC_TO_TIME(duration)) as end_date," +
            "banner_link,type," +
            "(Case WHEN type=0 THEN CONCAT(?,banner_photo) else banner_photo END) as banner_photo "+
            "from banners ",[path],
            // "having ? between start_date AND end_date ",[path,date],

            function(err,banners){
                if(!!err) throw err;

                if(banners.length==0) {
                    return callback(banners);
                }
                else {
                    for(var i in banners) {
                        banners[i]['start_date'] = moment(banners[i]['start_date']).tz(time_zone).format('D.M.YYYY \\a\\t h.ma');
                        banners[i]['end_date'] = moment(banners[i]['end_date']).tz(time_zone).format('D.M.YYYY');
                    }
                    return callback(banners);
                }
            });
    }
    if(type=="middle")
    {
        db.query("select name,contact,email,location,price,duration,duration_type," +
            "start_date," +
            "ADDTIME(start_date,SEC_TO_TIME(duration)) as end_date," +
            "banner_link,type," +
            "CONCAT(?,banner_photo) as banner_photo " +
            "from banners where type =?" ,[path,0],
            // "having ? between start_date AND end_date ",[path,date],

            function(err,banners){
                if(!!err) throw err;

                if(banners.length==0) {
                    return callback(banners);
                }
                else {
                    for(var i in banners) {
                        banners[i]['start_date'] = moment(banners[i]['start_date']).tz(time_zone).format('D.M.YYYY \\a\\t h.ma');
                        banners[i]['end_date'] = moment(banners[i]['end_date']).tz(time_zone).format('D.M.YYYY');
                    }
                    return callback(banners);
                }
            });
    }
};

//get trade live/upcoming/ended
async function getTrade(type, user_id, sub_cat_id, per_page,time_zone, time_array, callback, state_id, area_id, is_preferred, search, time) {

    const data = await getQueryData(type, sub_cat_id, per_page, time, user_id, state_id, area_id, is_preferred, search, time_zone, time_array);
    //execute query and set response
    db.query(data.query,data.data_arr, async function(err,postFav) {

        if(!!err) throw err;

        if(postFav.length==0) {
            let resArr = [];
            if(type == "HomeUpcomming") {

                resArr['HomeUpcomming'] = postFav;
                resArr['time_array'] = data.time_arr;

            } else if(type == "HomeLive" || type == "HomeLiveSeller") {

                resArr[type] = postFav;
                resArr['time_array'] = data.time_arr;

            } else {
                resArr = postFav;
            }
            return callback(resArr);
        } else {

            for(var i in postFav) {

                postFav[i]['product_images'] = await getPostImage(postFav[i]['product_pk_id']);
                postFav[i]['post_rating'] = await getPostRating(postFav[i]['product_pk_id']);
                postFav[i]['post_review'] = await getPostReview(postFav[i]['product_pk_id']);
                postFav[i]['seller_detail'] = await getSellerDetails(postFav[i]['user_profile_id']);
                postFav[i]['address'] = postFav[i]['area'] + ', ' + postFav[i]['state'];
                postFav[i]['weight'] = postFav[i]['qty'] + ' ' + postFav[i]['unit'];
                if(type == "HomeEnd"){
                    postFav[i]['buyers_detail'] = await getBuyerDetail(postFav[i]['post_id'],time_zone);
                }
                if(type == "HomeLive" || type == "HomeEnd"){
                    postFav[i]['price_drop'] = await price_drop_history(postFav[i]['post_id'], time_zone);
                }
                postFav[i]['post_start_date'] = moment(postFav[i]['post_start_date']).tz(time_zone).format('D.M.YYYY \\a\\t hh.mm a');
                postFav[i]['post_end_date'] = moment(postFav[i]['post_end_date']).tz(time_zone).format('D.M.YYYY');
                postFav[i]['post_start_at'] = moment(postFav[i]['post_start_at']).tz(time_zone).format('YYYY-MM-DD hh:mm:ss A');
                postFav[i]['post_end_at'] = moment(postFav[i]['post_end_at']).tz(time_zone).format('YYYY-MM-DD hh:mm:ss A');
            }

            let resArr = [];
            if(type == "HomeUpcomming") {

                resArr['HomeUpcomming'] = postFav;
                resArr['time_array'] = data.time_arr;

            } else if(type == "HomeLive" || type == "HomeLiveSeller") {

                resArr[type] = postFav;
                resArr['time_array'] = data.time_arr;

            } else {
                resArr = postFav;
            }
            return callback(resArr);
        }
    });
}

//set Query
function getQueryData(type, sub_cat_id, per_page, time, user_id, state_id, area_id, is_preferred, search, time_zone, time_array) {

    return new Promise( async function(resolve , reject ){

        var dt = new Date();
        var date = dateFormat(dt, "UTC:yyyy-mm-dd HH:MM:ss");

        var query = "select DISTINCT(po.id) as post_id,p.product_name,p.id as product_pk_id," +
            "p.user_profile_id,up.user_type,p.main_category_id," +
            "p.sub_category_id,p.imported as is_imported,p.grade_id,p.url,p.pickup_point,p.description," +
            "p.fast_buy,p.fast_buy_price,p.is_mygap,p.is_organic,p.repost,po.id as post_id," +
            "po.frame,p.status,po.starting_price,po.second_price,po.third_price,po.fourth_price," +
            "po.ended_price,po.qty,po.weight_unit_id,po.credit_fee,up.id as user_id," +
            "p.species_id,p.other_species,p.other_imported_info,"+
            "(select CONCAT('',name) AS grade from grades where grades.id = p.grade_id) AS grade,"+
            "(select name from states where states.id = p.state_id) AS state,"+
            "(select name from areas where areas.id = p.area_id) AS area,"+
            "IFNULL((select name from species where species.id = p.species_id), '') AS species,"+
            "(select unit from weight_units where weight_units.id = po.weight_unit_id) AS unit,"+
            "p.product_id as display_post_id, po.date_time AS post_start_date, DATE_ADD(po.date_time, INTERVAL frame SECOND) as post_end_date ,"+
            "po.date_time AS post_start_at, DATE_ADD(po.date_time, INTERVAL frame SECOND) as post_end_at ,"+
            "(Case WHEN up.is_approved_status = 1 THEN up.preferred_status " +
            "ELSE 0 " +
            "END) as is_preferred, " +
            "(Case WHEN " +
            "EXISTS (select id from favourite_posts " +
            "where post_id = po.id and user_profile_id = ?) then 1 " +
            "ELSE 0 " +
            "END) as is_favourite " +
            "from products as p " +
            "join posts as po " +
            "join user_profiles as up on p.user_profile_id = up.id " +
            "and p.id = po.product_id " +
            "where po.deleted_at IS NULL " +
            "and p.deleted_at IS NULL ";

        console.log("---------" + type + "----------");
        console.log("Date Time: " + date);


        let data_arr = [];
        let time_arr = [];
        data_arr.push(user_id);
        if(sub_cat_id) {
            query += " and p.sub_category_id = ? ";
            data_arr.push(sub_cat_id);
        }
        if(state_id) {
            query += " and p.state_id = ? ";
            data_arr.push(state_id);
        }
        if(area_id) {
            query += " and p.area_id = ? ";
            data_arr.push(area_id);
        }
        if(search) {
            query += " and p.product_name LIKE ? ";
            data_arr.push("%"+search+"%");
        }

        //------- for buyer ------
        if (type == "HomeLive") {

            query += " and po.is_pause = 0 " +
                " and ? >= po.date_time AND ? < DATE_ADD(po.date_time, INTERVAL po.frame SECOND)" +
                " and can_show = 1 ";
            data_arr.push(date);
            data_arr.push(date);

            if(is_preferred) {
                query += " Having is_preferred = ? ";
                data_arr.push(is_preferred);
            }
            query += " ORDER BY `po`.`id` ASC";

            time_arr = await get_start_time(time_array, time_zone);

        } else if (type == "HomeEnd") {

            query += " and po.is_pause = 0 " +
                " and ((? >= DATE_ADD(po.date_time, INTERVAL po.frame SECOND)) or (can_show = 0))";
            data_arr.push(date);

            if(is_preferred) {
                query += " Having is_preferred = ? ";
                data_arr.push(is_preferred);
            }
            query += " ORDER BY `po`.`id` DESC ";

        } else if (type == "HomeUpcomming") {

            let dates;
            if(time) {
                dates = await get_upcoming_time_wise_date(time_array, time, time_zone);
            } else {
                dates = await get_upcoming_date(time_array, time_zone);
            }

            let newStartDate = dates[0];
            let newEndDate = dates[1];
            time_arr = dates[2];

            console.log("Given Date Time upcoming start : " + newStartDate);
            console.log("Given Date Time upcoming end : " + newEndDate);

            if(time)
            {

                query += " and po.is_pause = 0 " +
                    " and po.date_time = ? " +
                    " and can_show = 1 ";

                data_arr.push(newStartDate);
                // data_arr.push(newEndDate);

            }
            else
            {

                query += " and po.is_pause = 0 " +
                    " and po.date_time >= ? " +
                    " and can_show = 1 ";
                data_arr.push(newStartDate);

            }
            console.log("data_arr: "+data_arr);

            if(is_preferred) {
                query += " Having is_preferred = ? ";
                data_arr.push(is_preferred);
            }
            query += " ORDER BY `po`.`date_time` ASC";

        } else if (type == "HomeLiveSeller") { //------- for seller ------

            query += " and po.is_pause = 0 " +
                " and ? >= po.date_time AND ? < DATE_ADD(po.date_time, INTERVAL po.frame SECOND)" +
                " and can_show = 1 "+
                " and p.user_profile_id = ? "+
                " ORDER BY `po`.`id` ASC";

            data_arr.push(date);
            data_arr.push(date);
            data_arr.push(user_id);

            time_arr = await get_start_time(time_array, time_zone);
        }

        if(per_page) {
            query += " LIMIT 0, ?";
            data_arr.push(per_page);
        }

        var data_array = {
            'query': query,
            'data_arr':data_arr,
            'time_arr':time_arr
        };

        console.log("---------" + type + "----------");

        return resolve(data_array);
    });
}

//get seller detail
function getSellerDetails(seller_id) {

    return new Promise( function(resolve , reject ){

        var sql = "select id,name,profile_pic,user_type," +
            "main_category_id,sub_category_id," +
            "(select name from states where states.id = state_id) AS state,"+
            "(select name from areas where areas.id = area_id) AS area,"+
            "address as company_address " +
            "from user_profiles where id = ?";

        db.query(sql,[seller_id],async function(err,data) {

            if(!!err) throw err;
            var res_array;
            if(data.length > 0){
                data[0]['user_type'] = user_type[data[0]['user_type']];
                data[0]['seller_rating'] = await getSellerRating(seller_id);
                data[0]['seller_review'] = await getSellerReview(seller_id);
                data[0]['as_buyer_review'] = await asBuyerReview(seller_id);
                data[0]['profile_pic'] = (data[0]['profile_pic']) ? path + data[0]['profile_pic'] : '';
                data[0]['main_category_id'] = (data[0]['main_category_id']) ? data[0]['main_category_id'] : 0;
                data[0]['sub_category_id'] = (data[0]['sub_category_id']) ? data[0]['sub_category_id'] : 0;

                res_array = data[0];
            }else{
                res_array = {};
            }

            return resolve(res_array);
        });
    });

}

//get seller rating-average
function getSellerRating(seller_id) {

    return new Promise( function(resolve , reject ){

        var sql = "select ROUND(AVG(rate),2) as rate " +
            "from `ratings` " +
            "where `ratings`.`seller_id` is not null " +
            "and `ratings`.`deleted_at` is null " +
            "and `ratings`.`seller_id` = ? "+
            "group by `seller_id`";

        db.query(sql,[seller_id],function(err,data) {

            if(!!err) throw err;
            var seller_rating;
            if(data.length > 0){
                seller_rating = (data[0]['rate']) ? data[0]['rate'] : 0.00;
            }else{
                seller_rating = 0.00;
            }

            return resolve(seller_rating);
        });
    });

}

//get seller review-count
function getSellerReview(seller_id) {

    return new Promise( function(resolve , reject ){

        var sql = "select count(*) as review " +
            "from `ratings` " +
            "where `ratings`.`seller_id` is not null " +
            "and `ratings`.`review` is not null " +
            "and `ratings`.`review` != '' " +
            "and `ratings`.`deleted_at` is null " +
            "and `ratings`.`seller_id` = ? "+
            "group by `seller_id`";

        db.query(sql,[seller_id],function(err,data) {

            if(!!err) throw err;
            var seller_review;
            if(data.length > 0){
                seller_review = data[0]['review'];
            }else{
                seller_review = 0;
            }

            return resolve(seller_review);
        });
    });

}

//get seller review-count
function asBuyerReview(seller_id) {

    return new Promise( function(resolve , reject ){

        var sql = "select count(*) as review " +
            "from `ratings` " +
            "where `ratings`.`buyer_id` is not null " +
            "and `ratings`.`review` is not null " +
            "and `ratings`.`review` != '' " +
            "and `ratings`.`deleted_at` is null " +
            "and `ratings`.`buyer_id` = ? "+
            "group by `buyer_id`";

        db.query(sql,[seller_id],function(err,data) {

            if(!!err) throw err;
            var seller_review;
            if(data.length > 0){
                seller_review = data[0]['review'];
            }else{
                seller_review = 0;
            }

            return resolve(seller_review);
        });
    });

}

//get buyer detail
function getBuyerDetail(post_id,time_zone) {

    return new Promise( function(resolve , reject ){

        var sql = "select user_profiles.id,credit_managements.id as credit_management_id," +
            "credit_managements.bid_price as bid_price," +
            "credit_managements.created_at as purchase_date," +
            "user_profiles.name as buyer_name,user_profiles.profile_pic " +
            "from `credit_managements` " +
            "join `user_profiles` on `user_profiles`.`id` = `credit_managements`.`buyer_id` " +
            "where `credit_managements`.`post_id` = ?  " +
            "and `credit_managements`.`deleted_at` is null  " +
            "and `user_profiles`.`deleted_at` is null";

        db.query(sql,[post_id],async function(err,data) {

            if(!!err) throw err;
            var res_array;
            if(data.length > 0) {
                for (var i in data){
                    data[i]['purchase_date'] = moment(data[i]['purchase_date']).tz(time_zone).format('D.M.YYYY \\a\\t hh.mm a');
                    data[i]['profile_pic'] = (data[i]['profile_pic']) ? path + data[i]['profile_pic'] : '';
                }
                res_array = data[0];
            }else {
                res_array = {};
            }
            return resolve(res_array);
        });
    });

}

//get post rating-average
function getPostRating(product_id) {

    return new Promise( function(resolve , reject ){

        var sql = "select ROUND(avg(`rate`),2) as rate " +
            "from `posts` join `credit_managements` " +
            "on `posts`.`id` = `credit_managements`.`post_id` " +
            "join `ratings` on `credit_managements`.`id` = `ratings`.`credit_management_id` " +
            "where `posts`.`product_id` = ? " +
            "and `ratings`.`deleted_at` is null " +
            "and `credit_managements`.`deleted_at` is null " +
            "and `posts`.`deleted_at` is null";

        db.query(sql,[product_id],function(err,data) {

            if(!!err) throw err;
            var post_rating;
            if(data.length > 0){
                post_rating = (data[0]['rate']) ? data[0]['rate'] : 0.00;
            }else{
                post_rating = 0.00;
            }

            return resolve(post_rating);
        });
    });

}

//get post review-count
function getPostReview(product_id) {

    return new Promise( function(resolve , reject ){

        var sql = "select count(*) as review from " +
            "`posts` join `credit_managements` on `posts`.`id` = `credit_managements`.`post_id` " +
            "join `ratings` on `credit_managements`.`id` = `ratings`.`credit_management_id` " +
            "where `posts`.`product_id` = ? " +
            "and `ratings`.`deleted_at` is null " +
            "and `credit_managements`.`deleted_at` is null " +
            "and `ratings`.`review` is not null " +
            "and `ratings`.`review` != '' " +
            "and `posts`.`deleted_at` is null";

        db.query(sql,[product_id],function(err,data) {

            if(!!err) throw err;
            var post_review;
            if(data.length > 0){
                post_review = data[0]['review'];
            }else{
                post_review = 0;
            }

            return resolve(post_review);
        });
    });

}

//get post images
function getPostImage(product_id) {

    return new Promise( function(resolve , reject ){

        var sql = "select image " +
            "from `product_images`" +
            "where `product_images`.`product_id` = ? " ;

        db.query(sql,[product_id],function(err,data) {

            if(!!err) throw err;
            if(data.length > 0){
                for (var i in data){
                    data[i]['image'] = (data[i]['image']) ? path + data[i]['image'] : '';
                }
            }
            return resolve(data);
        });
    });

}

//get logistics company photos
function get_logistics_company_photos(company_id) {

    return new Promise(function (resolve, reject) {

        let sql = "select CONCAT(?,image) as image " +
            "from logistic_photos " +
            "where logistic_company_id = ? ";

        db.query(sql,[path,company_id],function (err,data) {

            if(!!err) throw err;
            return resolve(data);
        })
    });
}

//create time-array for upcoming filtering
async function time_array(time_zone, callback) {
    let data = {"time_zone":time_zone};
    let promise = await new Promise( async function(resolve , reject ){
        try {
            let response = await axios.post(api_path +'get_time_array',data);
            return resolve(response.data['Data']);
        } catch (error) {
            return resolve([]);
        }
    });
    return callback(promise);
}

//get start/end date from current date
function get_upcoming_date(time_array, time_zone) {
    return new Promise( function(resolve , reject ){
        let output = time_array.filter(function(value) {
            return value.date == moment().tz(time_zone).format("YYYY-MM-DD");
        });

        let res_time_array = output;
        let start_date;
        let end_date;

        if(output.length == 0){
            start_date = time_array[0]['date']+' '+time_array[0]['time'];
            end_date = time_array[1]['date']+' '+time_array[1]['time'];
            res_time_array = time_array;

        }else if(output.length == 1){
            start_date = output[0]['date']+' '+output[0]['time'];
            end_date = time_array[0]['date']+' '+time_array[0]['time'];
            res_time_array = time_array;

        }else{
            start_date = output[0]['date']+' '+output[0]['time'];
            end_date = output[1]['date']+' '+output[1]['time'];
        }

        let timeZoneSTDate = moment.tz(start_date, time_zone);
        let newStartDate = dateFormat(timeZoneSTDate, "UTC:yyyy-mm-dd HH:MM");
        let timeZoneENDate = moment.tz(end_date, time_zone);
        let newEndDate = dateFormat(timeZoneENDate, "UTC:yyyy-mm-dd HH:MM");

        return resolve([newStartDate,newEndDate,res_time_array]);
    });
}

//get start/end date from given time
function get_upcoming_time_wise_date(time_array,time, time_zone) {

    return new Promise( function(resolve , reject ){

        let key;
        time_array.filter(function(value, index) {
            if(value.time == time){
                key = index;
            }
        });

        let res_time_array = time_array.filter(function(value) {
            return value.date == moment().tz(time_zone).format("YYYY-MM-DD");
        });

        let start_date;
        let end_date;

        if(key == (time_array.length - 1)) {
            start_date = time_array[key]['date']+' '+time_array[key]['time'];
            end_date = time_array[0]['date']+' '+time_array[0]['time'];
            // res_time_array = time_array;
        }else {
            start_date = time_array[key]['date']+' '+time_array[key]['time'];
            end_date = time_array[key++]['date']+' '+time_array[key++]['time'];
        }

        let timeZoneSTDate = moment.tz(start_date, time_zone);
        let newStartDate = dateFormat(timeZoneSTDate, "UTC:yyyy-mm-dd HH:MM");
        let timeZoneENDate = moment.tz(end_date, time_zone);
        let newEndDate = dateFormat(timeZoneENDate, "UTC:yyyy-mm-dd HH:MM");

        return resolve([newStartDate,newEndDate,res_time_array]);
    });
}

function price_drop_history(post_id, time_zone) {

    return new Promise( function(resolve , reject ){

        var sql = "select date_time as start_date," +
            "starting_price,second_price,third_price,fourth_price,ended_price,frame from `posts`" +
            "where `posts`.`id` = ? " +
            "and `posts`.`deleted_at` is null";

        db.query(sql,[post_id],async function(err,data) {

            if(!!err) throw err;
            let price_array;
            let price_drop_array = [];
            if(data.length > 0){
                price_array = [ data[0]['starting_price'],
                    data[0]['second_price'],
                    data[0]['third_price'],
                    data[0]['fourth_price'],
                    data[0]['ended_price'] ];
                let price_drop = await time_drop_array(data[0]['start_date'], time_zone, data[0]['frame']);
                for(let i in  price_drop) {
                    let temp = {};
                    temp['time'] = price_drop[i];
                    temp['price'] = price_array[i];

                    price_drop_array.push(temp);
                }
            }else{
                price_drop_array = [];
            }

            return resolve(price_drop_array);
        });
    });
}

function time_drop_array(start_date,time_zone,frame_interval) {

    return new Promise( function(resolve , reject ){

        let local_time = moment.tz(start_date,time_zone).format('YYYY-MM-DD HH:mm:ss');
        let time_drop = [];

        frame_interval = parseInt(frame_interval);
        let frame = parseInt(parseInt(frame_interval)/5);

        let sec = 0;
        while(sec < frame_interval){
            let parsedDate = new Date(local_time);
            let newDate = parsedDate.setSeconds(parsedDate.getSeconds() + sec);
            time_drop.push(dateFormat(newDate, "HH:MM"));
            sec += frame;
        }
        return resolve(time_drop);
    });
}

function get_start_time(time_array, time_zone) {

    return new Promise( function(resolve , reject ){

        let date;
        let time;
        let output = time_array.filter(function(value) {
            return value.date > moment().tz(time_zone).format("YYYY-MM-DD");
        });

        date = moment().tz(time_zone).format("YYYY-MM-DD");
        if(output.length > 0){
            time = output[output.length - 1]['time'];
        } else {
            time = "00:00";
        }
        return resolve([date,time]);
    });

}
