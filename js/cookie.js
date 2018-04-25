const cookie = (function() {
	const showCookie = function(name) {
	    if (document.cookie != "") {
	        const cookies = document.cookie.split(/; */);

	        for (let i=0; i<cookies.length; i++) {
	            const cookieName = cookies[i].split("=")[0];
	            const cookieVal = cookies[i].split("=")[1];
	            if (cookieName === decodeURIComponent(name)) {
	                return decodeURIComponent(cookieVal);
	            }
	        }
	    }
	};

	const setCoockie = function(value) {
	    if (navigator.cookieEnabled) {
	        const cookieVal = encodeURIComponent(value);
	        let cookieText = "displayedLand=" + cookieVal;
	        const data = new Date();
	        data.setTime(data.getTime() + (365 * 24*60*60*1000));
	        cookieText += "; expires=" + data.toGMTString();

	        document.cookie = cookieText;
	    }
	};

	const deleteCookie = function(name) {
	    const cookieName = encodeURIComponent(name);
	    document.cookie = cookieName + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
	};

	return {
		showCookie : showCookie,
		setCoockie : setCoockie,
		deleteCookie : deleteCookie
	}
}());