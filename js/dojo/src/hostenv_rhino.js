/* Copyright (c) 2004-2005 The Dojo Foundation, Licensed under the Academic Free License version 2.1 or above *//*
* Rhino host environment
*/

// make jsc shut up (so we can use jsc for sanity checking) 
/*@cc_on
@if (@_jscript_version >= 7)
var loadClass; var print; var load; var quit; var version; var Packages; var java;
@end
@*/

if (typeof loadClass == 'undefined') dj_throw("attempt to use Rhino host environment when no 'loadClass' global");

dojo.hostenv.name_ = 'rhino';
dojo.hostenv.getVersion = function() {return version()};

// see comments in spidermonkey loadUri
dojo.hostenv.loadUri = function(uri, cb){
	dj_debug("uri: "+uri);
	try{
		// FIXME: what about remote URIs?
		var found = true;
		if(!(new java.io.File(uri)).exists()){
			try{
				// try it as a file first, URL second
				(new java.io.URL(uri)).openStream();
			}catch(e){
				found = false;
			}
		}
		if(!found){
			dj_debug(uri+" does not exist");
			if(cb){ cb(0); }
			return 0;
		}
		var ok = load(uri);
		// dj_debug(typeof ok);
		dj_debug("rhino load('", uri, "') returned. Ok: ", ok);
		if(cb){ cb(1); }
		return 1;
	}catch(e){
		dj_debug("rhino load('", uri, "') failed");
		if(cb){ cb(0); }
		return 0;
	}
}

dojo.hostenv.println = print;
dojo.hostenv.exit = function(exitcode){ 
	quit(exitcode);
}

// Hack to determine current script...
//
// These initial attempts failed:
//   1. get an EcmaError and look at e.getSourceName(): try {eval ("static in return")} catch(e) { ...
//   Won't work because NativeGlobal.java only does a put of "name" and "message", not a wrapped reflecting object.
//   Even if the EcmaError object had the sourceName set.
//  
//   2. var e = Packages.org.mozilla.javascript.Context.getCurrentContext().reportError('');
//   Won't work because it goes directly to the errorReporter, not the return value.
//   We want context.interpreterSourceFile and context.interpreterLine, which are used in static Context.getSourcePositionFromStack
//   (set by Interpreter.java at interpretation time, if in interpreter mode).
//
//   3. var e = Packages.org.mozilla.javascript.Context.getCurrentContext().reportRuntimeError('');
//   This returns an object, but e.message still does not have source info.
//   In compiler mode, perhaps not set; in interpreter mode, perhaps not used by errorReporter?
//
// What we found works is to do basically the same hack as is done in getSourcePositionFromStack,
// making a new java.lang.Exception() and then calling printStackTrace on a string stream.
// We have to parse the string for the .js files (different from the java files).
// This only works however in compiled mode (-opt 0 or higher).
// In interpreter mode, entire stack is java.
// When compiled, printStackTrace is like:
// java.lang.Exception
//	at sun.reflect.NativeConstructorAccessorImpl.newInstance0(Native Method)
//	at sun.reflect.NativeConstructorAccessorImpl.newInstance(NativeConstructorAccessorImpl.java:39)
//	at sun.reflect.DelegatingConstructorAccessorImpl.newInstance(DelegatingConstructorAccessorImpl.java:27)
//	at java.lang.reflect.Constructor.newInstance(Constructor.java:274)
//	at org.mozilla.javascript.NativeJavaClass.constructSpecific(NativeJavaClass.java:228)
//	at org.mozilla.javascript.NativeJavaClass.construct(NativeJavaClass.java:185)
//	at org.mozilla.javascript.ScriptRuntime.newObject(ScriptRuntime.java:1269)
//	at org.mozilla.javascript.gen.c2.call(/Users/mda/Sites/burstproject/testrhino.js:27)
//    ...
//	at org.mozilla.javascript.tools.shell.Main.main(Main.java:76)
//
// Note may get different answers based on:
//    Context.setOptimizationLevel(-1)
//    Context.setGeneratingDebug(true)
//    Context.setGeneratingSource(true) 
//
// Some somewhat helpful posts:
//    http://groups.google.com/groups?hl=en&lr=&ie=UTF-8&oe=UTF-8&safe=off&selm=9v9n0g%246gr1%40ripley.netscape.com
//    http://groups.google.com/groups?hl=en&lr=&ie=UTF-8&oe=UTF-8&safe=off&selm=3BAA2DC4.6010702%40atg.com
//
// Note that Rhino1.5R5 added source name information in some exceptions.
// But this seems not to help in command-line Rhino, because Context.java has an error reporter
// so no EvaluationException is thrown.

// do it by using java java.lang.Exception
function dj_rhino_current_script_via_java(depth) {
    var optLevel = Packages.org.mozilla.javascript.Context.getCurrentContext().getOptimizationLevel();  
    if (optLevel == -1) dj_unimplemented("getCurrentScriptURI (determine current script path for rhino when interpreter mode)", '');
    var caw = new java.io.CharArrayWriter();
    var pw = new java.io.PrintWriter(caw);
    var exc = new java.lang.Exception();
    exc.printStackTrace(pw);
    var s = caw.toString();
    // we have to exclude the ones with or without line numbers because they put double entries in:
    //   at org.mozilla.javascript.gen.c3._c4(/Users/mda/Sites/burstproject/burst/Runtime.js:56)
    //   at org.mozilla.javascript.gen.c3.call(/Users/mda/Sites/burstproject/burst/Runtime.js)
    var matches = s.match(/[^\(]*\.js\)/gi);
    if(!matches){
		throw Error("cannot parse printStackTrace output: " + s);
	}

    // matches[0] is entire string, matches[1] is this function, matches[2] is caller, ...
    var fname = ((typeof depth != 'undefined')&&(depth)) ? matches[depth + 1] : matches[matches.length - 1];
    var fname = matches[3];
	if(!fname){ fname = matches[1]; }
    // print("got fname '" + fname + "' from stack string '" + s + "'");
    if (!fname) throw Error("could not find js file in printStackTrace output: " + s);
    //print("Rhino getCurrentScriptURI returning '" + fname + "' from: " + s); 
    return fname;
}

// UNUSED: leverage new support in native exception for getSourceName
/*
function dj_rhino_current_script_via_eval_exception() {
    var exc;
    // 'ReferenceError: "undefinedsymbol" is not defined.'
    try {eval ("undefinedsymbol()") } catch(e) {exc = e;}
    // 'Error: whatever'
    // try{throw Error("whatever");} catch(e) {exc = e;}
    // 'SyntaxError: identifier is a reserved word'
    // try {eval ("static in return")} catch(e) { exc = e; }
    print("got exception: '" + exc + "'");
    print("exc.stack=" + (typeof exc.stack));
    var sn = exc.getSourceName();
    print("SourceName=" + sn);
    return sn;
} 
*/

// reading a file from disk in Java is a humiliating experience by any measure.
// Lets avoid that and just get the freaking text
function readText(uri){
	// NOTE: we intentionally avoid handling exceptions, since the caller will
	// want to know
	var jf = new java.io.File(uri);
	var sb = new java.lang.StringBuffer();
	var input = new java.io.BufferedReader(new java.io.FileReader(jf));
	var line = "";
	while((line = input.readLine()) != null){
		sb.append(line);
		sb.append(java.lang.System.getProperty("line.separator"));
	}
	return sb.toString();
}

// call this now because later we may not be on the top of the stack
if (!dojo.hostenv.library_script_uri_) dojo.hostenv.library_script_uri_ = dj_rhino_current_script_via_java(1);


