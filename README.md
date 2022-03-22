# CodeObfuscator
Code Obfuscator written in parametric javascript

This program can be run either client-side only of using a PHP server (no Node.js is needed). See Nativity section to make it run client-side only by alterting only two lines of the code.

Copyright
---------
The code was written by Stephen Duffy and is released under a GPL2.0 licence.

Anyone can use it, distribute it or modify it, even commercially, but credit must be given to the author.


Use Case
--------

This is a tool for obfuscating source code for the pupoposes of proving plagiarism and making reverse engineering more difficult.

It is suitable for use with a variety of languages but generally speaking, the tool will be useful for those writing in languages where the source code is by nature exposed. For example Javascript.

Nativity
--------
The program is provided as a php file but has redundant methods within it which will enable a simple modification so that the program can be run client-side only. The program, as it is, is almost entirely Javascript, apart from one method "PHPsaveScript()" which uses an XMLHttpRequest to save the obfuscated file to the disk. Contained within the program is another method "JSsaveScript()" which can be used as an alternative method of downloading the obfuscated file to a location of the users choice. This is two extra dialogues and is a pain but more compatible if you have no apache/nginx.

To alter to pure javascript, simply remove the part of the first line of the file which is <?PHP ... ?> then go to near the bottom of the file in the javascript function init() and change the line "saveCode=PHPsaveCode;" tp "saveCode=JSsaveCode".

Usage
-----
This program is a tool and does not provide a "click and obfuscate" type interface as you might expect. It allows you to obfuscate one variable throughout the target at a time, but there is a batch command which will allow those obfuscations you have tried and tested on your program to be carried out again without you having to type them in. The batch processor is as near as it gets to "click and obfuscate" but you will have to build the script the batch processor processes by yourself, tailoring your batch script to each file of source code carefully.

Hints
-----
When carrying out obfuscations, avoid obfuscating variables which may have the same name as a keyword of javascript or one of its apis if you are using them in your code. For example, if you have a javascript class Objy with a method getElementById(), obfuscating the method name in this instance may have undesirable effects if elsewhere you use the method getElementById of the javascript class document. Obviously you dont want to obfuscate keywords like "for", "if", "while", etc. Try to stick the variables which are unique to your program and are unlike those which are named in the various APIs available.

The way it works is very intutiive and you can see the results of each obfuscation before saving the obduscated source code and trying it out.

Credits
-------
Stephen Duffy, lead programmer at Gartbeth Digital Services Ltd.
