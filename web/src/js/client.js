/**
 * Created by Jaime on 08/01/2016.
 */

var cmdPrompt;
var ready = true;
var term;
var cmdHistory = [];
var cmdIterator = null;

$(document).ready(function(event) {
    cmdPrompt = $('input#cmd');
    term = $('div#terminalOutput');
    cmdPrompt.focus();

    cmdPrompt.keypress(function (e) {
        if (e.which == 13) {
            sendCommand();
            return false;
        } else  if (e.keyCode == 38) {
            history(true);
        } else  if (e.keyCode == 40) {
            history(false);
        }
    });

    $(window).on('focus', function() { cmdPrompt.focus();});
});

function sendCommand(){
    if(!ready) return;

    cmd = cmdPrompt.val();
    cmdPrompt.val('');

    logCmd(cmd);

    if(cmd=='clear' || cmd=='cls'){
        term.empty();
        return;
    }

    ready = false;
    writeToConsole("<span class='command'>" + cmd + "</span>");
    $.post( "exec.php?uuid=" + uuid, {cmd: cmd}).done(onReply);
}


function onReply(data) {
    writeToConsole("<span class='output'>" + data + "</span>");
    ready=true;
}

function logCmd(cmd){
    if(cmd != cmdHistory[cmdHistory.length-1]) cmdHistory.push(cmd);
    cmdIterator = cmdHistory.length;
}

function history(isUp){
    if(cmdIterator===null) return;
    switch (isUp){
        case true:
            if(cmdIterator>0) cmdIterator--;
            break;
        case false:
            if(cmdIterator<cmdHistory.length-1) cmdIterator++;
            break;
    }
    cmdPrompt.val(cmdHistory[cmdIterator]);
}

function writeToConsole(text){
    term.append(text);
    $(document).scrollTop($(document).height());

}
