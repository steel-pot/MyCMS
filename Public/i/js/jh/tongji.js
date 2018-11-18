function reqRest(url, types, callback) {
    $.getJSON(url + "&r=" + Math.random(), function (json) {
        callback(json, types);
    });
}

function reqRest1(url, callback) {
    $.getJSON(url + "&r=" + Math.random(), function (json) {
        callback(json);
    });
}

function count(o) {
    var t = typeof o;
    if (t == 'string') {
        return o.length;

    } else if (t == 'object') {
        var n = 0;
        for (var i in o) {
            n++;
        }
        return n;
    }
    return false;
}


function StartRun() {
    if (GameType == "CLTS") {
        reqRest1('../../zl/	jh_json_clts.aspx?ContentType=json', setCl);
    }
    else {
        fg1 = GameType.split("_");
        if (count(fg1) != 2) { return; };
        reqRest('/web/jh_json_api.html?ContentType=json&name=' + fg1[0].toLowerCase() + '&t=' + fg1[1], 1, do_JSON_jihua);
    }
}

var T;
var DEFAULT_TIME_D = 10;
function gameKanJiangDataC(diffTime) {

    diffTime = Number(diffTime);
    m = Math.floor(diffTime % 60),
        s = (diffTime-- - m) / 60;
    if (s < 10) {
        s = "0" + s;
    }

    if (m < 10) {
        m = "0" + m;
    }

    if (s > 60) {
        h = Math.floor(s / 60);
        if (h < 10) {
            h = "0" + h;
        }
        s = s - h * 60;
        $('.left_time').html(h);
        $('.right_time').html(s);
    } else {
        h = 0;
        $('.left_time').html(s);
        $('.right_time').html(m);
    }

    if (h == 0 && m == 0 && s == 0) {
        show_xiaqis();
    } else {
        clearTimeout(T);
        T = setTimeout('gameKanJiangDataC(' + diffTime + ')', 1000);

        if (DEFAULT_TIME_D == 0) {
            DEFAULT_TIME_D = 10;
        }
        else {
            DEFAULT_TIME_D--;
        }
    }
}


function show_xiaqis() {
    var html = '正在更新计划，请稍后...'
    $("#clock").html(html);
    $('#multiple').html(html);
    StartRun();
}

function xiaqi(kjdata) {
    var actionNo = Number(kjdata) + 1;
    var xiaqi = actionNo;
    if (actionNo == 120) {
        var now = new Date();
        var yy = now.getFullYear();      //年
        var mm = now.getMonth() + 1;     //月
        var dd = now.getDate();          //日
        xiaqi = yy + mm + dd + '001';
    }
    return xiaqi;
}


function do_JSON_jihua(result, shtype) {
    fg1 = GameType.split("_");
    if (count(fg1) != 2) { return; }
    if (result != null) {
        var need_qishu = $('#now_qishu').html();
        var WaitGame = 0;

        if (result.GameMultiple != null && result.GameMultiple != undefined) {
            WaitGame = result.GameMultiple.NextGameID;
        };
		console.log(WaitGame);
        var diffTime = open_time(WaitGame);
        if (result.TopGame != null && result.TopGame != undefined) {

            if (WaitGame == 0 && need_qishu.length == 0) {
                //大发快3 江苏快3 安徽快3 广西快3 湖北快3 北京快3 河北快3 甘肃快3 上海快3 贵州快3 吉林快3
                if (fg1[0] == "DFK3" || fg1[0] == "JSK3" || fg1[0] == "AHK3" || fg1[0] == "GXK3" || fg1[0] == "HUBK3" || fg1[0] == "BJK3" || fg1[0] == "HEBK3" || fg1[0] == "GSK3" || fg1[0] == "SHK3" || fg1[0] == "GZK3" || fg1[0] == "JLK3") {
                    $('#now_qishu').text(result.TopGame.gameid);
                    $('#show_qiu').html('<div>' + '&#12288;' + '</div><span>' + result.TopGame.R1 + '</span><span>' + result.TopGame.R2 + '</span><span>' + result.TopGame.R3 + '</span><div>' + '&#12288;' + '</div>');
                    console.log('值1：', result.TopGame.gameid);
                }
                else {
                    $('#now_qishu').text(result.TopGame.gameid);
                    $('#show_qiu').html('<span>' + result.TopGame.R1 + '</span><span>' + result.TopGame.R2 + '</span><span>' + result.TopGame.R3 + '</span><span>' + result.TopGame.R4 + '</span><span>' + result.TopGame.R5 + '</span>');
                    console.log('值1：', result.TopGame.gameid);
                }
            } else if (WaitGame != 0 && diffTime == 0) {
                if (fg1[0] == "DFK3" || fg1[0] == "JSK3" || fg1[0] == "AHK3" || fg1[0] == "GXK3" || fg1[0] == "HUBK3" || fg1[0] == "BJK3" || fg1[0] == "HEBK3" || fg1[0] == "GSK3" || fg1[0] == "SHK3" || fg1[0] == "GZK3" || fg1[0] == "JLK3") {
                    $('#now_qishu').text(result.GameMultiple.NextGameID);
                    $('#show_qiu').html('<div>&#12288;</div><span>开</span><span>奖</span><span>中</span><div>&#12288;</div>');
                    console.log('值2：', result.TopGame.gameid);
                }
                else {
                    $('#now_qishu').text(result.GameMultiple.NextGameID);
                    $('#show_qiu').html('<span>=</span><span>开</span><span>奖</span><span>中</span><span>=</span>');
                    console.log('值2：', result.TopGame.gameid);
                }
            } else if (WaitGame != 0 && diffTime > 0) {
                $('#now_qishu').text(result.TopGame.gameid);
                console.log('值3：', result.TopGame.gameid);

                if (GameType == "11X5") {

                    var R1 = "0" + result.TopGame.R1
                    var R2 = "0" + result.TopGame.R2
                    var R3 = "0" + result.TopGame.R3
                    var R4 = "0" + result.TopGame.R4
                    var R5 = "0" + result.TopGame.R5

                    $('#show_qiu').html('<span>' + R1.substr(-2) + '</span><span>' + R2.substr(-2) + '</span><span>' + R3.substr(-2) + '</span><span>' + R4.substr(-2) + '</span><span>' + R5.substr(-2) + '</span>');

                } else if (fg1[0] == "PK10" || fg1[0] == "DFPK10") {

                    var R1 = "0" + result.TopGame.R1
                    var R2 = "0" + result.TopGame.R2
                    var R3 = "0" + result.TopGame.R3
                    var R4 = "0" + result.TopGame.R4
                    var R5 = "0" + result.TopGame.R5
                    var R6 = "0" + result.TopGame.R6
                    var R7 = "0" + result.TopGame.R7
                    var R8 = "0" + result.TopGame.R8
                    var R9 = "0" + result.TopGame.R9
                    var R10 = "0" + result.TopGame.R10

                    $('#show_qiu').html('<em class="car-1">' + R1.substr(-2) + '</em><em class="car-2">' + R2.substr(-2) + '</em><em class="car-3">' + R3.substr(-2) + '</em><em class="car-4">' + R4.substr(-2) + '</em><em class="car-5">' + R5.substr(-2) + '</em><em class="car-6">' + R6.substr(-2) + '</em><em class="car-7">' + R7.substr(-2) + '</em><em class="car-8">' + R8.substr(-2) + '</em><em class="car-9">' + R9.substr(-2) + '</em><em class="car-10">' + R10.substr(-2) + '</em>');

                } else if (fg1[0] == "DFK3" || fg1[0] == "JSK3" || fg1[0] == "AHK3" || fg1[0] == "GXK3" || fg1[0] == "HUBK3" || fg1[0] == "BJK3" || fg1[0] == "HEBK3" || fg1[0] == "GSK3" || fg1[0] == "SHK3" || fg1[0] == "GZK3" || fg1[0] == "JLK3") {
                    $('#show_qiu').html('<div>' + '&#12288;' + '</div><span>' + result.TopGame.R1 + '</span><span>' + result.TopGame.R2 + '</span><span>' + result.TopGame.R3 + '</span><div>' + '&#12288;' + '</div>');
                } else {

                    $('#show_qiu').html('<span>' + result.TopGame.R1 + '</span><span>' + result.TopGame.R2 + '</span><span>' + result.TopGame.R3 + '</span><span>' + result.TopGame.R4 + '</span><span>' + result.TopGame.R5 + '</span>');
                }
            }
        };

        if (WaitGame > 0 && diffTime > 0) {
            var $left_time = $('.left_time').text();
            var $right_time = $('.right_time').text();

            if ($left_time == '' || $right_time == '') {
                $left_time = '00';
                $right_time = '00';
            }
            if (fg1[0] == "PK10" || fg1[0] == "BJK3") {
                var html = '下期 <em id="next_qishu" style="color:#ff0000">' + WaitGame + '</em> 期倒计时：<span class="time_box"><em class="left_time">' + $left_time + '</em><em class="center_time">:</em><em class="right_time">' + $right_time + '</em></span>'
            } else if (fg1[0] == "DFSSC" || fg1[0] == "DFK3" || fg1[0] == "DFPK10") {
                var html = '下期 <em id="next_qishu" style="color:#ff0000">' + WaitGame.substr(-4) + '</em> 期倒计时：<span class="time_box"><em class="left_time">' + $left_time + '</em><em class="center_time">:</em><em class="right_time">' + $right_time + '</em></span>'
            }
            else {
                var html = '下期 <em id="next_qishu" style="color:#ff0000">' + WaitGame.substr(-2) + '</em> 期倒计时：<span class="time_box"><em class="left_time">' + $left_time + '</em><em class="center_time">:</em><em class="right_time">' + $right_time + '</em></span>'
            }

            $("#clock").html(html);

            setTimeout('gameKanJiangDataC(' + diffTime + ')', 1000);
        } else {
            setTimeout('show_xiaqis()', 5000);
        }

        if (shtype == 1) {

            if (GameType == "CLTS") {
                setCl(result);
            } else {
                setGameList(result);
            }
        }
    } else {
        setTimeout('show_xiaqis()', 5000);
    }
}


function setGameList(gameList) {
    fg1 = GameType.split("_");

    if (count(fg1) != 2) { return; }
    var li = '';
    if (gameList.NewGame != null && gameList.NewGame != undefined) {
        li = '<li>' + gameList.NewGame.WaitGame.replace('等开', '<font color="#f9ee30">等开</font>') + '</li>';
    };

    if (gameList.EndList != null && gameList.EndList.length > 0) {
        for (var i = 0; i < gameList.EndList.length; i++) {
            li = li + '<li>' + gameList.EndList[i].Ruestl.replace('对', '<font color="#e82f2f">中</font>').replace('错', '<font color="#1bd357">挂</font>') + '</li>';
            //if(i>=11){break;}
        }
    }

    $('#jihua_list').html(li);

    if (gameList.GameMultiple != null && gameList.GameMultiple != undefined) {
        if (fg1[0] == "PK10" || fg1[0] == "CQSSC" || fg1[0] == "BJK3") {
            $('#multiple').html(gameList.GameMultiple.Gt + '【' + gameList.GameMultiple.num + '】' + gameList.GameMultiple.NextGameID.substr(-3) + ' 期 <font color="#ff0000">' + gameList.GameMultiple.Multiple + '</font> 倍<span class="beitou"></span>');
        } else if (fg1[0] == "DFSSC" || fg1[0] == "DFK3" || fg1[0] == "DFPK10") {
            $('#multiple').html(gameList.GameMultiple.Gt + '【' + gameList.GameMultiple.num + '】' + gameList.GameMultiple.NextGameID.substr(-4) + ' 期 <font color="#ff0000">' + gameList.GameMultiple.Multiple + '</font> 倍<span class="beitou"></span>');
        } else {
            $('#multiple').html(gameList.GameMultiple.Gt + '【' + gameList.GameMultiple.num + '】' + gameList.GameMultiple.NextGameID.substr(-2) + ' 期 <font color="#ff0000">' + gameList.GameMultiple.Multiple + '</font> 倍<span class="beitou"></span>');
        }
    }
}


function setCl(gameList) {
    var li = '';
    console.log('值1：', gameList.ClData);
    console.log('值2：', gameList.ClData.length);
    if (gameList.ClData != null && gameList.ClData.length > 0) {
        console.log('值2：', GameType);
        var conf0 = ["北京PK10冠军", "重庆时时彩万位", "大发时时彩万位", "大发快3", "大发PK10冠军", "江苏快3", "安徽快3", "广西快3", "湖北快3", "北京快3", "河北快3", "甘肃快3", "上海快3", "贵州快3", "吉林快3"];
        var conf1 = ["大小长龙", "大小长龙", "大小长龙", "大小长龙", "大小长龙", "大小长龙", "大小长龙", "大小长龙", "大小长龙", "大小长龙", "大小长龙", "大小长龙", "大小长龙", "大小长龙", "大小长龙"];
        var conf11 = ["pk10_11", "cqssc_11", "dfssc_11", "dfk3_11", "dfpk10_11", "jsk3_11", "ahk3_11", "gxk3_11", "hubk3_11", "bjk3_11", "hebk3_11", "gsk3_11", "shk3_11", "gzk3_11", "jlk3_11"];
        var conf2 = ["单双长龙", "单双长龙", "单双长龙", "单双长龙", "单双长龙", "单双长龙", "单双长龙", "单双长龙", "单双长龙", "单双长龙", "单双长龙", "单双长龙", "单双长龙", "单双长龙", "单双长龙"];
        var conf21 = ["pk10_12", "cqssc_12", "dfssc_12", "dfk3_12", "dfpk10_12", "jsk3_12", "ahk3_12", "gxk3_12", "hubk3_12", "bjk3_12", "hebk3_12", "gsk3_12", "shk3_12", "gzk3_12", "jlk3_12"];

        var num1 = ["1", "11", "21", "31", "41", "51", "61", "71", "81", "91", "101", "111", "121", "131", "141"];

        var n = 1;
        for (var i = 0; i < conf0.length; i++) {
            jsonObj1 = gameList.ClData[toInt(num1[i]) - 1];
            jsonObj2 = gameList.ClData[toInt(num1[i])];
            li = li + '<li class="clbox clt1"><span class="clboxtop"><span class="clbox0">' + conf0[i] + '</span></span><span class="clboxtop"><span class="clbox0">' + conf1[i] + '</span></span><span class="clboxtop"><span class="clbox0">' + jsonObj1[conf11[i]] + '</span></span><span class="clboxtop"><span class="clbox0">' + conf2[i] + '</span></span><span class="clboxtop"><span class="clbox0">' + jsonObj2[conf21[i]] + '</span></span></li>';

            li = li + '<li class="clbox clt2"></li>';
        }
    }
    $('#jihua_list').html(li);
    setTimeout('show_xiaqis()', 5000);
}


function toInt(number) {
    return number && + number | 0 || 0;
}
