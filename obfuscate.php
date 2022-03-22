<?php if (isset($_GET['fn'])) file_put_contents($_GET['fn'],file_get_contents('php://input')); ?><!doctype html><html><head><script type='text/javascript'>
var saveCodeElement;
var con,src,tgt,varnum;
var helptext='\
h:	The help command shows this text.\n\
l:	Load code into the target.\n\
s:	Save obfuscated code.\n\
r:	Replace whole words by specifying what to find and what to replace it with.\n\
b:	Execute a batch of a commands saved in a file.\n\
';
function loadCode(){
	var file=loadCodeDlg.files[0];
	if(file){
		var reader = new FileReader();
		reader.onerror=function(e){if(e.target.error.name=="NotReadableError") alert('Error reading file');};
		reader.onload=function(e){tgt.value=e.target.result;con.focus();};
		reader.readAsText(file, "UTF-8");
  }
}
function PHPsaveCode(){
	try{fn=loadCodeDlg.files[0].name;}
	catch(ex){fn='new';}
	xhr=new XMLHttpRequest();
	xhr.open('post',location.pathname+'?fn=obfs_'+fn);
	xhr.send(tgt.value);
	println('Saved to directory this page is hosted from as \"obfs_'+fn+'\".');
}
function JSsaveCode(){	//(filename,textInput){
	try{fn=loadCodeDlg.files[0].name;}
	catch(ex){fn='new';}
	fn='obfs_'+fn;
	saveCodeElement.setAttribute('href','data:text/plain;charset=utf-8,'+encodeURIComponent(tgt.value));
	saveCodeElement.setAttribute('download',fn);
	saveCodeElement.click();
}
function replaceCommand(f,r=null){
	if (!r) {r='obfs_'+varnum; varnum++}
	tgt.value=replaceMost(f,r,replaceMost(f,r,tgt.value));
}
function runBatch(){
	var file=loadBatchDlg.files[0];
	if(file){
		var reader = new FileReader();
		reader.onerror=function(e){if(e.target.error.name=="NotReadableError") alert('Error reading file');};
		reader.onload=function(e){
			batch=e.target.result.split('\n');
			for (var i=0;i<batch.length;i++){
				println(batch[i]);
				cmd=batch[i].split(' ');
				switch (cmd[0]){
					case 'r':
						replaceCommand(cmd[1],cmd[2]);
						break;
				}
			}
		};
		reader.readAsText(file, "UTF-8");
  }
}
function process(command){
	cmd=command.split(' ');
	switch (cmd[0]){
		case 'h':
		case '?':
		case 'help':	print(helptext); break;
		case 'l':		loadCodeDlg.click(); break;
		case 's':		saveCode(); break;
		case 'b':		loadBatchDlg.click(); break;
		case 'r':		if ((cmd.length<2)||(cmd.length>3)) println('SYNTAX: r [find] [replace]');
							else replaceCommand(cmd[1],cmd[2]);
							break;
	}
}
function inString(s,pos){
	qtype=0;	//0=none,1=',2="
	for (i=0;i<pos;i++){
		if (s[i]=="'"){
			if (qtype==0) qtype=1;
			else if (qtype==1) qtype=0;
		}
		else if (s[i]=='"'){
			if (qtype==0) qtype=2;
			else if (qtype==2) qtype=0;
		}
	}
	return qtype!==0;
}
function replaceMost(f,r,input){
	//the regex exec will not replace two adjacent "finds" if they are separated
	//by only one "non-word" character, running this twice is guaranteed to catch
	//all, but once will only catch one match in something like "x.FIND=FIND;".
	regex=new RegExp('\\W'+f+'\\W','g');
	let cur=0;
	let match;
	let output='';
	while ((match=regex.exec(input))!==null){
		pos=match.index;
		pre=input.substring(cur,pos+1);
		if (inString(input,pos+1)) output+=pre+f;
		else output+=pre+r;
		cur=regex.lastIndex-1;
	}
	output+=input.substr(cur);
	return output;
}
function consoleHandler(e){
	if (e.key=='Enter'){
		end=con.selectionStart;
		start=con.value.substring(0,end).lastIndexOf('\n')+1;
		if (start<0) start=0;
		print('\n');
		process(con.value.substring(start,end));
		e.preventDefault();
	}
}
function print(text){
	ss=con.selectionStart;
	pre=con.value.substr(0,ss);
	post=con.value.substr(ss);
	con.value=pre+text+post;
	con.setSelectionRange(ss+text.length+1,ss+text.length+1);
}
function println(text){
	print(text+'\n');
}
function init(){
	varnum=0;

	document.body.style='color:#eee;background:#222;margin:0;text-align:center;';

	tgt=document.createElement('textarea');
	tgt.style='margin:0;padding:0;border:none;background:#004;color:#eee;font-size:1.125rem;';
	tgt.style.width=innerWidth+'px';
	tgt.style.height=((innerHeight>>1))+'px';
	document.body.appendChild(tgt);

	con=document.createElement('textarea');
	con.style='margin:0;padding:0;border:none;background:#000;color:#eee;font-size:1.125rem;';
	con.style.width=(innerWidth)+'px';
	con.style.height=((innerHeight>>1))+'px';
	con.addEventListener('keypress',consoleHandler);
	document.body.appendChild(con);

	loadCodeDlg=document.createElement('input');
	loadCodeDlg.addEventListener('change',loadCode);
	loadCodeDlg.type='file';

	loadBatchDlg=document.createElement('input');
	loadBatchDlg.addEventListener('change',runBatch);
	loadBatchDlg.type='file';

	saveCodeElement=document.createElement('a');
	saveCode=PHPsaveCode;
//	saveCode=JSsaveCode;

	println('Code Obfuscator by Auranos.org. Copyright 2022. All Rights Reserved.');
	println('Type h for help.');
	con.focus();
}
</script></head><body onload='init();'></body></html>