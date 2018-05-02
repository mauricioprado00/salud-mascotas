(function(){
	Function.prototype.bindEachAsMethod = function () {
	    var __method = this, args = $A(arguments);//, object = args.shift();
	    return function (object, idx) {
			return __method.apply(object, args);
		};
	}
	Function.prototype.bindEachAsMethodizedFunction = function () {
	    var __method = this, args = $A(arguments);//, object = args.shift();
	    return function (object, idx) {
			return __method.apply(window, [ object ].concat(args));
		};
	}
}());