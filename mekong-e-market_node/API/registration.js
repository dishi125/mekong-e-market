var connection = require("../config/db");
var base64 = require('base-64');
var randtoken = require('rand-token');
var path = require('path');
var dateTime = require('node-datetime');
var dateFormat = require('dateformat');

module.exports = function(req,res) {
    var name = req.body.name,
        email = req.body.email,
        password = base64.encode(req.body.password);
    // console.log(password);
    var profilePic = req.files.profile_pic;
    var phone_no=req.body.phone_no,
        user_type=req.body.user_type,
        category_id=req.body.category_id,
        company_name=req.body.company_name,
        company_reg_no=req.body.company_reg_no,
        company_tel_no=req.body.company_tel_no,
        state_id=req.body.state_id,
        area_id=req.body.area_id,
        address=req.body.address,
        company_email=req.body.company_email,
        doc=req.files.document,
        preferred=req.body.preferred_status,
        is_approved=req.body.is_approved_status;
   // console.log(doc);
    //res.send(path.extname(doc.name));
    connection.query("select * from `user_profiles` where email=?", [email], function (er, dataArray) {
        if (!!er) throw er;
        if (dataArray.length == 0) {
            if (req.files === 0) {

            } else {
                var ext = path.extname(profilePic.name);
                profilePic.name = randtoken.generate(5) + Date.now() + ext;
                var img_path='mekong-e-market_node/profile_pics/' + profilePic.name;
                profilePic.mv(__dirname + '/../profile_pics/' + profilePic.name, function (err) {
                    if (err) return res.status(500).send(err);
                });

                var docExt = path.extname(doc.name);
                //console.log("doc ext:" +docExt);
                doc.name = randtoken.generate(5) + Date.now() + docExt;
                var doc_path='mekong-e-market_node/documents/' + doc.name;
                doc.mv(__dirname + '/../documents/' + doc.name, function (err) {
                    if (err) return res.status(500).send(err);
                });
            }
            var dt = dateTime.create();
            var currdt = dt.format('Y-m-d H:M:S');
            connection.query("INSERT INTO `user_profiles` (`name`, `email`, `password`, `profile_pic`, `phone_no`, `user_type`, `main_category_id`, `company_name`, `company_reg_no`, `company_tel_no`, `state_id`, `area_id`, `address`, `company_email`, `document`, `preferred_status`, `is_approved_status`,`created_at`,`updated_at`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);", [name,email,password,img_path,phone_no,user_type,category_id,company_name,company_reg_no,company_tel_no,state_id,area_id,address,company_email,doc_path,preferred,is_approved,currdt,currdt], function (err, result) {
                if (!!err) throw err;
                connection.query("select name,email,profile_pic as 'profile pic',phone_no as 'phone number',user_type,main_category_id as 'category',company_name,company_reg_no,company_tel_no,state_id as state,area_id as area,address,company_email,document from `user_profiles` where email=?", [email], function (err1, result1) {
                    if (!!err1) throw err1;
                    if(result1.length==0){
                        return res.json({success: "false", message:"user not found"});
                    }
                    else {
                        return res.json({success: "true",content:result1, message: "registration successfully"});
                    }
                });
                return result;
            });
        } else {
            return res.json({success: "false", message:"user already exists"});
        }
    });
};
