var hierarchicalFacet;

(function ($) {

	hierarchicalFacet = function(path) {
		var path,
			delimiter = '/',
			getPath,
			getLevel,
			splitPath,
			increaseLevel,
			getLast,
			getName,
			getParentPath;

		this.getPath = function() {
			return path;
		}

		this.getName = function() {
			var splitPath = this.splitPath();
			var level = splitPath.shift();
			return splitPath.join(delimiter);

		};
		this.getLevel = function() {
			var splitPath = this.splitPath();
			var level = splitPath.shift();
			return level;

		};

		this.removeLast = function() {
			var splitPath = this.splitPath();
			var last = splitPath.pop();
			return splitPath.join(delimiter);
		};

		this.splitPath = function() {
			return path.split(delimiter);
		};

		this.getPreviousFq = function() {
			var splitPath = this.splitPath();
			var last = splitPath.pop();
			var level = splitPath.shift();
			level--;
			return level + delimiter + splitPath.join(delimiter);
		}

		this.increaseLevel = function() {
			var splitPath = this.splitPath();
			var level = splitPath.shift();
			level ++;
			return level + delimiter + splitPath.join(delimiter);
		};

		this.getLast = function() {
			var splitPath = this.splitPath();
			var last = splitPath.pop();
			return last;
		}
	}

})(jQuery);
